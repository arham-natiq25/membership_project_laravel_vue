@extends('layouts.master')

@section('title')
Customer Details
@endsection

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Customers Details </h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <div class="card-title"> Hello , <span class="text-primary">{{Auth()->user() ->name}}</span></div>
        </div>
        <div class="card-body">
         <customer/>

        </div>
        <!-- /.card-body -->


      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>

@endsection