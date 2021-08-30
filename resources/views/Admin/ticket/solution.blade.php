@extends('Admin.index')
@section('body')
@php
use App\Http\Controllers\BackEndCon\Controller;
use App\Http\Controllers\BackEndCon\ticketController;
@endphp

<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js"></script>

<div class="row">

    <div class="col-md-12">
       @include('error.msg')
       
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject bold uppercase">Give A Solution</span>
                </div>
                <div class="tools"> </div>
            </div>
            <div class="portlet-body">
               <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="icon-settings font-dark"></i>
                        <span class="caption-subject bold uppercase">Ticket</span>
                    </div>
                </div>
                <br>
              <table class="table table-bordered" >
                <thead>
                  <tr>
                    <th>Ticket Code</th>
                    <th>Ticket Topic</th>
                    <th>Ticket Description</th>
                    <th>Ticket Submitted By</th>
                    <th>Ticket Submitted At</th>
                    <th>Total Solution</th>
                  </tr>
                </thead>
                @if(isset($ticket) && count($ticket)>0)
                   <tr class="gradeX" id="tr-{{$ticket->ticket_id }}">
                      <td>{{$ticket->ticket_code}}</td>
                      <td>{{$ticket->ticket_topic}}</td>
                      <td>{{$ticket->ticket_desc}}</td>
                      <td>@php echo Controller::getSeniorName($ticket->ticket_submitted_by); @endphp</td>
                      <td>{{$ticket->ticket_submitted_at}}</td>
                      <td>@php echo ticketController::numberOfSolution($ticket->ticket_id); @endphp</td>
                    </tr>
                @endif
              </table>
            </div>

            <div class="portlet-body">
               <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="icon-settings font-dark"></i>
                        <span class="caption-subject bold uppercase">All Solutions</span>
                    </div>
                </div>
                <br>
              <table class="table table-bordered" >
                <thead>
                  <tr>
                    <th>Ticket Code</th>
                    <th>Ticket Topic</th>
                    <th>Ticket Description</th>
                    <th>Ticket Submitted By</th>
                    <th>Ticket Submitted At</th>
                  </tr>
                </thead>
                @if(isset($solution) && count($solution)>0)
                  @foreach ($solution as $sl)
                   <tr class="gradeX" id="tr-{{$sl->ticket_id }}">
                      <td>{{$sl->ticket_code}}</td>
                      <td>{{$sl->ticket_topic}}</td>
                      <td>{{$sl->ticket_desc}}</td>
                      <td>@php echo Controller::getSeniorName($sl->ticket_submitted_by); @endphp</td>
                      <td>{{$sl->ticket_submitted_at}}</td>
                    </tr>
                  @endforeach
                @endif
              </table>
            </div>

            <div class="portlet-body">
               <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="icon-settings font-dark"></i>
                        <span class="caption-subject bold uppercase">Submit New Solution</span>
                    </div>
                </div>
              <br>
              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('ticket-solution')}}/{{$ticket->ticket_id}}" name="basic_validate" id="data_form">
              {{ csrf_field() }}
                <div class="form-group">
                  
                  <label class="col-md-2 control-label">
                    Ticket Topic: <span class="required">* </span>
                  </label>

                  <div class="col-md-8">
                    <input type="text" class="form-control" name="ticket_topic" value="{{old('ticket_topic')}}" required>
                  </div>

                </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Ticket Description: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <textarea class="form-control" name="ticket_desc" style="resize: none;height: 150px">{{old('ticket_desc')}}</textarea>
                </div>

              </div>
 

               <div class="form-group">

                <label class="col-md-2 control-label">
              
                </label>

                <div class="col-md-3">
                  <input type="submit" value="Submit New Ticket" class="btn btn-success">
                </div>                

              </div>
              </form>
            </div>

          </div>
        </div>
      </div>
    </div>
@endsection