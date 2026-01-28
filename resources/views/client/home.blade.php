@extends('client.layout.master')

@section('title', 'Home Page')

@section('content')


<main class="container my-5">
    <div class="d-flex justify-content-center mb-5">
        <form class="row row-cols-lg-auto g-2 align-items-center" action="{{route('search')}}" method="POST">
            @csrf
            <div class="col-12">
                <input
                    type="text"
                    class="form-control"
                    placeholder="doctor name"
                    name="doctor-specialty">
            </div>

            <div class="col-12">
                <select class="form-select" name="city">
                    <option selected>city</option>
                    @foreach($cities as $city)
                    <option value="1">{{$city->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    find it
                </button>
            </div>
        </form>
    </div>

    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-lg-3 col-md-4">
            <!-- City select -->
            <div class="mb-4">
                <select class="form-select">
                    <option selected>choose city</option>
                    @foreach ($cities as $city)
                    <option value="{{ $city->id }}" {{$currentCity == $city->id ? 'selected' : ''}}>
                        {{ $city->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Specialties list -->
            <div class="list-group">
                @foreach ($specialties as $specialty)
                <a href="{{route('doctors-with-city-specialty', ['city' => $currentCity ,
                 'specialty' => $specialty->id])}}"
                    class="list-group-item list-group-item-action">
                    {{ $specialty->title }}
                </a>
                @endforeach
            </div>
        </div>

        <!-- Doctors -->
        <div class="col-lg-9 col-md-8">
            <div class="row g-4">
                @forelse ($doctors as $doctor)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ asset('/images/doctors/'.$doctor->image) }}"
                            class="card-img-top"
                            alt="{{ $doctor->name }}">

                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title mb-1">{{ $doctor->name }}</h6>
                            @foreach($doctor->specialties as $specialty)
                            <small class="text-muted mb-2">
                                {{ $specialty->title  }}
                            </small>
                            @endforeach()


                            <a href="#"
                                class="btn btn-outline-primary btn-sm mt-auto">
                                view profile
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-warning">
                        پزشکی برای این شهر / تخصص ثبت نشده است.
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

</main>


@endsection