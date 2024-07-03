<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class BookController extends Controller
{
    //This method will list all books
    public function index(Request $request)
    {
        $books = Book::orderBy('created_at', 'DESC');
        if (!empty($request->keyword)) {
            $books->where('title', 'like', '%' . $request->keyword . '%');
        }
        $books = $books->paginate(10);
        return view('books.list', compact('books'));
    }
    //This method will show create book page
    public function create()
    {
        return view('books.create');
    }
    //This method will store a book in database
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|min:5|string',
            'author' => 'required|min:3|string',

            'status' => 'required|integer'
        ];
        if (!empty($request->image)) {
            $rules['image'] = 'image';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->route('books.create')->withInput()->withErrors($validator);
        }

        //Save Book in DB
        $book = new Book();
        $book->title = $request->title;
        $book->author = $request->author;
        $book->description = $request->description;
        $book->status = $request->status;
        $book->save();

        //Upload book image here
        if (!empty($request->image)) {
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time() . '.' . $ext;
            $image->move(public_path('uploads/books'), $imageName);
            $book->image = $imageName;
            $book->save();
            //Intervention image for creating thumbnail image
            $manager = new ImageManager(Driver::class);
            $img = $manager->read(public_path('uploads/books/' . $imageName));
            $img->resize(990);
            $img->save(public_path('uploads/books/thumb/' . $imageName));
        }



        //Redirect to book listing
        return redirect()->route('books.index')->with('success', 'Book Successfully Created');
    }
    //This method will show edit book page
    public function edit($id)
    {
        $book = Book::findOrFail($id);
        return view('books.edit', compact('book'));
    }
    //This method will update a book
    public function update($id, Request $request)
    {
        $book = Book::findOrFail($id);
        $rules = [
            'title' => 'required|min:5|string',
            'author' => 'required|min:3|string',

            'status' => 'required|integer'
        ];
        if (!empty($request->image)) {
            $rules['image'] = 'image';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->route('books.edit', $book->id)->withInput()->withErrors($validator);
        }

        //Update Book in DB
        $book->title = $request->title;
        $book->author = $request->author;
        $book->description = $request->description;
        $book->status = $request->status;
        $book->save();

        //Upload book image here
        if (!empty($request->image)) {
            //This will old book image from book directory
            File::delete(public_path('uploads/books/' . $book->image));
            File::delete(public_path('uploads/books/thumb/' . $book->image));
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time() . '.' . $ext;
            $image->move(public_path('uploads/books'), $imageName);
            $book->image = $imageName;
            $book->save();

            $manager = new ImageManager(Driver::class);
            $img = $manager->read(public_path('uploads/books/' . $imageName));
            $img->resize(990);
            $img->save(public_path('uploads/books/thumb/' . $imageName));
        }

        //Intervention image for creating thumbnail image



        //Redirect to book listing
        return redirect()->route('books.index')->with('success', 'Book Successfully Updated');
    }
    //This method will delete a book
    public function destroy(Request $request)
    {
        $book = Book::find($request->id);
        if ($book == null) {
            session()->flash('error', 'Book Not Found');
            return response()->json([
                'status' => false,
                'message' => 'Book Not Found'
            ]);
        } else {
            File::delete(public_path('uploads/books/' . $book->image));
            File::delete(public_path('uploads/books/thumb/' . $book->image));
            $book->delete();
            session()->flash('success', 'Book deleted successfully');

            return response()->json([
                'status' => true,
                'message' => 'Book deleted successfully'
            ]);
        }
    }
}
