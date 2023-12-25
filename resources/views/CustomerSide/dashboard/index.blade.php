@extends('CustomerSide.layouts.master')

@section('title')
    Customer Dashboard
@endsection

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Customer Dashboard </h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-body">
           <welcome/> <br>

        </div>
        <!-- /.card-body -->


      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
@endsection
