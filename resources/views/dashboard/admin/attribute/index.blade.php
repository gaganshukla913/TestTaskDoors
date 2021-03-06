@extends('dashboard.base')
@section('content')
<style type="text/css">
    a.disabled {
        pointer-events: none;
        color: #ccc!important;
    }
    .uploaded-file-section{
        padding: 9px 11px;
        text-align: center;
        border: 1px solid #e6e6e6;
        margin: 24px 0px 1px 1px;
        color: #bdbdbd;
    }
    .img-bg {
        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;
        position: relative;
        /* padding-bottom: 100%; */
        height: 75px;
        width: 75px;
    }   
    .upload__img-box {
        display: inline-block;
    }
    .upload__img-close {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background-color: rgba(0, 0, 0, 0.5);
        position: absolute;
        /* top: 10px; */
        right: 0px;
        text-align: center;
        line-height: 24px;
        z-index: 1;
        cursor: pointer;
    }
    .upload__img-close:after {
        content: '\2716';
        font-size: 14px;
        color: white;
    } 
</style>
<div class="container-fluid">
    <div class="animated fadeIn">
        @include('flash') {{--session message--}}
        <div class="col-md-12 success"></div>
        <div class="row">
            <div class="col-sm-8 col-md-8 col-lg-8 col-xl-8">
                <div class="card db-table">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h5>Attribute Management</h5>
                            @if($attribute->exists)
                            <a href="{{route('attribute.index')}}" class="btn addbtn">
                                Add Attribute 
                            </a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        {!!
                            Form::model($attribute,[
                                'route' => $attribute->exists ? ['attribute.update',$attribute->id] : ['attribute.store'],
                                'method' => $attribute->exists ? 'PUT' : 'POST',
                                'files' => true,
                                'id' => 'form_attribute_id'
                            ])
                        !!}
                            @csrf
                            <div class="">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Attribute Title</label>
                                            <input type="text" class="form-control name" name="name" value="{{ $attribute['name'] ?? old('name')}}">
                                            <span class="name-error text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Attribute Type</label>
                                            {{-- {{$attribute['type'] ?? old('type')}} --}}
                                            <select name="type" class="form-control type">
                                                <option disabled selected>Select Type</option>
                                                <option value="input" {{ $attribute['type'] == 'input' ? 'selected' : '' }}>Input Box</option>
                                                <option value="dropdown" {{ $attribute['type'] == 'dropdown' ? 'selected' : '' }}>Dropdown</option>
                                                <option value="radio" {{ $attribute['type'] == 'radio' ? 'selected' : '' }}>Radio</option>
                                                <option value="check" {{ $attribute['type'] == 'check' ? 'selected' : '' }}>Checkbox</option>
                                            </select>
                                            <span class="type-error text-danger"></span>
                                            <input type="hidden" name="total_row" class="total_row" value="{{count($attribute['attributeDetails']) ?? 0}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-data">
                                @include('dashboard.admin.attribute.attribute-detail')
                            </div>
                            @if($attribute->exists)
                                <textarea name="remove-id" class="d-none"></textarea>
                            @endif
                           <div class="mt-3">
                                <button type="button" class="btn addbtn submit-button" value="{{$attribute->exists ? 'update' : 'save'}}">Save</button>
                           </div>

                            {{Form::close()}}
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                <div class="card db-table">
                    <div class="card-header">
                        <h5>Attribute List</h5>
                        {{-- <form method="GET" action="{{Request::segment(1) == 'admin' ? url('/admin/users') : url('/users')}}">
                            <div class="row">
                                <div class="col-md-2 align-self-center">
                                    <h5 class="title font-bold"> {{ __('Users') }} </h5>
                                </div>

                                <div class="col-md-3 text-center">
                                    <div class="search-via">
                                        <input type="text" name="name" value="{{ app('request')->input('name') }}" class="form-control" placeholder="Search via Name" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-3 text-center">
                                    <div class="search-via">
                                        <input type="text" name="email" value="{{ app('request')->input('email') }}" class="form-control" placeholder="Search via Email" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-4 text-right align-self-center add-owner">
                                    <div class="search-via">
                                        <button type="submit" class="btn btn-secondary" data-toggle="tooltip" title="Search"><i class="fa fa-search"></i></button>                                    
                                        <a href="{{ Request::segment(1) == 'admin' ? url('/admin/users') : url('/users') }}" class="btn btn-secondary ml-3" data-toggle="tooltip" title="Reset"><i class="fa fa-refresh"></i></a>
                                    </div>
                                
                                    <a href="{{ Request::segment(1) == 'admin' ? url('/admin/users/create') : url('/users/create') }}" class="btn addbtn ml-3"><span class="mr-1"><i class="fa fa-plus-circle" aria-hidden="true"></i></span> Add User</a>
                                </div>
                            </div>
                        </form> --}}
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped db-table">
                            <thead>
                                <tr>
                                    <th class="text-center">S.No.</th>
                                    <th>Title</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="searchDataTable">
                                @forelse($allAttribute as $key =>  $attribute) 
                                    <tr>
                                        <td class="text-center">{{$key+1}}.</td>
                                        <td>{{$attribute->name ?? ''}}</td>
                                        <td class="text-center">
                                            <a href="{{route('attribute.edit',$attribute->id)}}" class="btn" data-toggle="tooltip" title="Edit {{$attribute['name'] ?? ''}}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty 
                                    <tr>
                                        <td colspan="5">Data Not Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script src="{{asset('assets/backend/attribute/attribute.js')}}"></script>
@endsection