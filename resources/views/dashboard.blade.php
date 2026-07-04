<!-- resources/views/home.blade.php -->
@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="row">
                
                <div class="col-lg-4 col-md-4 order-1">
                  <div class="row">
                    <div class="col-lg-6 col-md-12 col-6 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                              <img
                                src="../assets/img/icons/unicons/chart-success.png"
                                alt="chart success"
                                class="rounded"
                              />
                            </div>
                          </div>
                          <span class="fw-semibold d-block mb-1">Leads</span>
                          <h3 class="card-title mb-2">{{$stats['leads']}}</h3>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-6 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                              <img
                                src="../assets/img/icons/unicons/wallet-info.png"
                                alt="Credit Card"
                                class="rounded"
                              />
                            </div>
                          </div>
                          <span>Team Members</span>
                          <h3 class="card-title text-nowrap mb-1">{{$stats['users']}}</h3>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
                  <div class="row">
                    <div class="col-6 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                              <img src="../assets/img/icons/unicons/paypal.png" alt="Credit Card" class="rounded" />
                            </div>
                          </div>
                          <span class="d-block mb-1">Customers</span>
                          <h3 class="card-title text-nowrap mb-2">{{$stats['customers']}}</h3>
                        </div>
                      </div>
                    </div>
                     <div class="col-6 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                              <img src="../assets/img/icons/unicons/paypal.png" alt="Credit Card" class="rounded" />
                            </div>
                          </div>
                          <span class="d-block mb-1">Pending Tasks</span>
                          <h3 class="card-title text-nowrap mb-2">{{$stats['pending_tasks']}}</h3>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <!-- Order Statistics -->
                <div class="col-md-6 col-lg-6 col-xl-6 order-0 mb-6">
                  <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                      <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Latest Activity Log</h5>
                      </div>
                      <div class="dropdown">
                        <button
                          class="btn p-0"
                          type="button"
                          id="orederStatistics"
                          data-bs-toggle="dropdown"
                          aria-haspopup="true"
                          aria-expanded="false"
                        >
                          <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="orederStatistics">
                          <a class="dropdown-item" href="{{route('activitylog')}}">View All</a>
                                                  </div>
                      </div>
                    </div>
                    <div class="card-body">

                      <ul class="p-0 m-0">
                      @foreach($activities as $activity)
                        <p>
                        <li class="d-flex mb-4 pb-1">
                            {{ $activity->created_at->diffForHumans() }}
                            -
                            {{ $activity->user->name }}
                            -
                            {{ $activity->description }}
                      
                        </li> 
                        </p>
                    @endforeach

                      </ul>
                    </div>
                  </div>
                </div>
                <!--/ Order Statistics -->
<div class="col-md-6 col-lg-6 col-xl-6 order-0 mb-6">
                  <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                      <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Today's Tasks</h5>
                      </div>

                    </div>
                    <div class="card-body">

                     <ul class="p-0 m-0">
                     <br>
                      @foreach($todaysTasks as $todaysTask)
                        <li class="pb-1">
                            <strong>{{ $todaysTask->title }}</strong>
                            <div>
                            @if($todaysTask->lead)
                                Lead:
                                <a href="{{ route('leads.tasks.edit', [$todaysTask->lead->id,$todaysTask->id,'from'=>'tasks']) }}">
                                    {{ $todaysTask->lead->name }}
                                </a>
                            @elseif($todaysTask->customer)
                                Customer:
                                <a href="{{ route('customers.tasks.edit', [$todaysTask->customer->id,$todaysTask->id,'from'=>'tasks']) }}">
                                    {{ $todaysTask->customer->name }}
                                </a>
                            @endif
                            </div>
                        </li> 
                    @endforeach

                      </ul>
                    </div>
                  </div>
                </div>
                
   
              </div>
@endsection