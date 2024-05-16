<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Danh sách xe</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
          crossorigin="anonymous">
</head>
<body>
<div class="container">
    <h1 class="my-4">Danh sách xe</h1>
    <p>Trương Văn Bình</p>
    <div class="text-left mb-3">
        <a name="btnThemMoi" id="" class="btn btn-success" href="{{ route('cars.create') }}" role="button">Thêm mới</a>
    </div>

    <!-- Search form -->
    <form method="GET" action="{{ route('cars.index') }}" class="form-inline mb-4">
        <input type="text" name="search" class="form-control mr-2" placeholder="Tìm kiếm theo nhà sản xuất" value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
        @if(request('search'))
            <a href="{{ route('cars.index') }}" class="btn btn-secondary ml-2">Quay lại</a>
        @endif
    </form>
    
    <!-- Display success message below "Add New" button -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col">
            @if($cars->isEmpty())
                <div class="alert alert-warning">
                    Không có xe nào được tìm thấy.
                </div>
            @else
                <table class="table table-striped table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center">STT</th>
                            <th class="text-center">Hãng xe</th>
                            <th class="text-center">Model</th>
                            <th class="text-center">Ngày sản xuất</th>
                            <th class="text-center">Ảnh</th>
                            <th class="text-center">Nhà sản xuất</th>
                            <th class="text-center">Chức năng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $stt = 1; @endphp  
                        @foreach ($cars as $car)
                            <tr>
                                <td class="text-center">{{ $stt++ }}</td>
                                <td class="text-center">{{ $car->description }}</td>
                                <td class="text-center">{{ $car->model }}</td>
                                <td class="text-center">{{ $car->produced_on }}</td>
                                <td class="text-center"><img src="{{ asset('image/' . $car->image) }}" alt="Ảnh xe" class="img-thumbnail" style="width: 100px;"></td>
                                <td class="text-center">{{ $car->mf ? $car->mf->mf_name : '' }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <a name="btnChitiet" id="" class="btn btn-primary" href="{{ route('cars.show', ['car' => $car->id]) }}" role="button">Chi tiết</a>
                                        <a name="btnSua" id="" class="btn btn-info" href="{{ route('cars.edit', ['car' => $car->id]) }}" role="button">Sửa</a>
                                        <form action="{{ route('cars.destroy', ['car' => $car->id]) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa xe này không?');" class="ml-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Xóa</button>
                                        </form>
                                    </div>    
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
</body>
</html>
