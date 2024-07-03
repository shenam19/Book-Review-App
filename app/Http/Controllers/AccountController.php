<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AccountController extends Controller
{
    //This method will show registration page
    public function index()
    {
        return view('account.register');
    }
    //This method will register user.
    public function processRegister(Request $request)
    {
        //Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:5',
            'password_confirmation' => 'required',

        ]);
        if ($validator->fails()) {
            return redirect()->route('account.register')->withInput()->withErrors($validator);
        }
        //Now register user
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->route('account.login')->with('success', 'You have successfully registered!');
    }
    public function login()
    {
        return view('account.login');
    }
    public function authenticate(Request $request)
    {
        //validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->route('account.login')->withInput()->withErrors($validator);
        }
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('account.profile')->with('success', 'Welcome to dashboard');
        } else {
            return redirect()->route('account.login')->withInput()->with('error', 'Invalid username or password');
        }
    }
    //This method will show user profile page
    public function profile()
    {
        $user = User::findOrFail(Auth::user()->id);

        return view('account.profile', compact('user'));
    }
    //Update user profile
    public function updateProfile(Request $request)
    {
        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . Auth::user()->id . ',id',
        ];
        if (!empty($request->image)) {
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg,gif,svg';
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->route('account.profile')->withInput()->withErrors($validator);
        }
        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();


        //Here we will update image

        if (!empty($request->image)) {

            //Delete old image here
            File::delete(public_path('uploads/profile/' . $user->image));
            //Delete old thumb picture
            File::delete(public_path('uploads/profile/thumb/' . $user->image));


            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time() . '.' . $ext; //12323423.jpg
            $image->move(public_path('uploads/profile'), $imageName);
            $user->image = $imageName;
            $user->save();
            //Intervention image for creating thumbnail image
            $manager = new ImageManager(Driver::class);
            $img = $manager->read(public_path('uploads/profile/' . $imageName));
            $img->cover(150, 150);
            $img->save(public_path('uploads/profile/thumb/' . $imageName));
        }

        return redirect()->route('account.profile')->with('success', 'Profile updated successfully');
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login')->with('success', 'Logout Successfully');
    }
    public function myReviews(Request $request)
    {
        $reviews = Review::with('book')->where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC');
        if (!empty($request->keyword)) {
            $reviews->where('review', 'like', '%' . $request->keyword . '%');
        }
        $reviews = $reviews->paginate(10);
        return view('account.my-reviews.my-reviews', compact('reviews'));
    }
    //This method will show edit form page of user's review
    public function editReview($id)
    {
        $review = Review::where([
            'id' => $id,
            'user_id' => Auth::user()->id,

        ])->first();
        return view('account.my-reviews.edit-review', compact('review'));
    }


    //This method will show edit review page
    public function edit($id)
    {
        $review = Review::findOrFail($id);
        return view('account.reviews.edit', compact('review'));
    }
    //This method will update review
    public function updateReview($id, Request $request)
    {
        $review = Review::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'review' => 'required',
            'rating' => 'required',

        ]);
        if ($validator->fails()) {
            return redirect()->route('account.myReviews.editReview', $id)->withInput()->withErrors($validator);
        }
        $review->review = $request->review;
        $review->rating = $request->rating;
        $review->save();
        session()->flash('success', 'Review updated successfully');
        return redirect()->route('account.myReviews');
    }

    //This method will delete review from database
    public function deleteReview(Request $request)
    {
        $id = $request->id;
        $review = Review::findOrFail($id);
        if ($review == null) {
            session()->flash('error', 'Review Not Found');
            return response()->json([
                'status' => false
            ]);
        } else {
            $review->delete();
            session()->flash('success', 'Review deleted successfully');
            return response()->json([
                'status' => true,
                'message' => 'Review deleted successfully'
            ]);
        }
    }
}
