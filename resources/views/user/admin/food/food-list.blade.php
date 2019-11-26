@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('messages.list.food.title') }}
@endsection

@section('custom_css')
@endsection

@section('content')
<div class="card">
    <div class="card-header text-uppercase text-primary font-weight-bold">
        {{ trans('messages.list.food.header') }}
    </div>

    @if(Session::has('success'))
        <div class="alert alert-success"><i class="fas fa-check"></i>
            {!! Session::get('success') !!}
        </div>
    @endif
    <div class="card-body">
        {!! $foods->appends(\Request::except('page'))->render() !!}
        <table class="table">
            <thead>
                <tr class="text-primary">
                    <th scope="col">{{ trans('messages.list.food.image') }}</th>
                    <th scope="col">@sortablelink('name',trans('messages.list.food.name'))</th>
                    <th scope="col">@sortablelink('type',trans('messages.list.food.type'))</th>
                    <th scope="col">@sortablelink('source',trans('messages.list.food.source'))</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($foods as $food)
                <tr>
                    <td><img src="{{ $food->image }}" width="100px" height="100px"></td>
                    <td>{{ $food->name }}</td>
                    <td>{{ trans('messages.type.'.$food->type) }}</td>
                    <td>{{ $food->source }}</td>
                    <td>
                        <!-- Detail button -->
                        <a href="#" class="badge badge-pill badge-info text-uppercase" data-toggle="modal" data-target="#foodDetailModal{{ $food->food_id }}">
                            {{ trans('messages.list.food.button.detail') }}
                        </a>
                        <!-- Detail modal -->
                        <div class="modal fade" id="foodDetailModal{{ $food->food_id }}" tabindex="-1" role="dialog" aria-labelledby="foodDetailModalTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-uppercase text-primary font-weight-bold">
                                            {{ $food->name }}
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body text-danger font-weight-bold">
                                        <p>{{ trans('messages.list.food.material') }}</p>
                                        <p>{{ $food->material }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                            {{ trans('messages.list.food.button.close') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- End detail modal -->
                        <!-- Edit button -->
                        <a href="#" class="badge badge-pill badge-primary text-uppercase">
                            {{ trans('messages.list.food.button.edit') }}
                        </a>
                        <!-- Delete button -->
                        <a href="#" class="badge badge-pill badge-danger text-uppercase" data-toggle="modal" data-target="#foodDeleteModal{{ $food->food_id }}">
                            {{ trans('messages.list.food.button.delete') }}
                        </a>
                        <!-- Delete modal -->
                        <div class="modal fade" id="foodDeleteModal{{ $food->food_id }}" tabindex="-1" role="dialog" aria-labelledby="foodDeleteModalTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-danger text-uppercase">
                                            {{ trans('messages.list.food.modal_title') }}
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                            {{ trans('messages.list.food.button.cancel') }}
                                        </button>
                                        <button type="button" class="btn btn-danger">
                                            <a href="food-delete-{{ $food->food_id }}">
                                                {{ trans('messages.list.food.button.delete') }}
                                            </a>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- End delete modal -->
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {!! $foods->appends(\Request::except('page'))->render() !!}
    </div>
</div>
@endsection
