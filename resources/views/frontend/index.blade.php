@extends('frontend.master')
@section('title')
    Frontend Membership
@endsection
@section('content')
<section class="content">

    <!-- Default box -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"></h3>
      </div>
      <div class="card-body">
        <div class="text-center">
            <welcome/>
        </div>
        <br>

        <front-page/>

      </div>
      <!-- /.card-body -->


    </div>
    <!-- /.card -->

  </section>

@endsection
