<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Mf;
use Illuminate\Http\Request;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->input('search');
        
        if ($query) {
            $cars = Car::whereHas('mf', function ($q) use ($query) {
                $q->where('mf_name', 'like', '%' . $query . '%');
            })->get();
        } else {
            $cars = Car::all();
        }
        
        return view('cars.index', ['cars' => $cars, 'search' => $query]);
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mfs = Mf::all(); // Assuming you have a Manufacturer model
        return view('cars.create', compact('mfs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the form data
        $request->validate([
            'description' => 'required',
            'model' => 'required',
            'produced_on' => 'required|date',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'mf_id' => 'required|exists:mfs,id',
        ]);

        $imageName = time().'.'.$request->image->getClientOriginalExtension();
        $request->image->move(public_path('image'), $imageName);

        // Create a new Car instance
        $car = new Car();
        $car->description = $request->description;
        $car->model = $request->model;
        $car->produced_on = $request->produced_on;
        $car->image = $imageName; // Save the image file name
        $car->mf_id = $request->mf_id;
        $car->save();

        // Redirect back to the list of cars
        return redirect()->route('cars.index')->with('success', 'Thêm mới xe thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $car = Car::find($id);
        return view('cars.show', compact('car'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $car = Car::findOrFail($id);
        $mfs = Mf::all(); // Fetch all manufacturers
        return view('cars.edit', compact('car', 'mfs'));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Tìm xe cần cập nhật thông tin
        $car = Car::findOrFail($id);

        // Validate the form data
        $request->validate([
            'description' => 'required',
            'model' => 'required',
            'produced_on' => 'required|date',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Kiểm tra hình ảnh (nếu cần)
        ]);

        // Update car information
        $car->description = $request->description;
        $car->model = $request->model;
        $car->produced_on = $request->produced_on;

        // Kiểm tra nếu có file ảnh mới được gửi lên
        if ($request->hasFile('image')) {
            // Lưu ảnh mới
            $imageName = time().'.'.$request->image->getClientOriginalExtension();
            $request->image->move(public_path('image'), $imageName);
            $car->image = $imageName;
        }

        // Kiểm tra nếu checkbox "Xóa ảnh" được chọn
        if ($request->has('delete_image')) {
            // Kiểm tra xem xe có ảnh hay không
            if ($car->image) {
                // Xóa ảnh hiện tại
                unlink(public_path('image/' . $car->image));
                // Đặt trường image thành null
                $car->image = null;
            }
        }

        // Lưu thông tin xe đã cập nhật
        $car->save();

        // Redirect về danh sách xe sau khi cập nhật thành công
        return redirect()->route('cars.index')->with('success', 'Thông tin xe đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Tìm xe cần xóa
        $car = Car::findOrFail($id);

        // Xóa ảnh của xe nếu có
        if ($car->image) {
            unlink(public_path('image/' . $car->image));
        }

        // Xóa xe
        $car->delete();

        // Chuyển hướng về trang danh sách xe với thông báo thành công
        return redirect()->route('cars.index')->with('success', 'Xóa xe thành công.');
    }
}
