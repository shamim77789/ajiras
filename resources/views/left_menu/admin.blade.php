<aside class="main-sidebar">
   <!-- sidebar: style can be found in sidebar.less -->
   <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
         <div class="pull-left image">
            <i class="fa fa-user" style="font-size: 60px"></i>
         </div>
         <div class="pull-left info">
            <p>{{ Sentinel::getUser()->first_name }} {{ Sentinel::getUser()->last_name }}</p>
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
         </div>
      </div>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
         <li class="@if(Request::is('dashboard')) active @endif">
            <a href="{{ url('dashboard') }}">
            <i class="fa fa-dashboard"></i> <span>{{trans_choice('general.dashboard',1)}}</span>
            </a>
         </li>
         @if(Sentinel::hasAccess('announcement'))
         <li class="treeview @if(Request::is('announcement/*')) active @endif">
            <a href="#">
               <i class="fa fa-bullhorn"></i> <span>{{trans_choice('general.announcement',2)}}</span>
               <span class="pull-right-container">
               <i class="fa fa-angle-left pull-right"></i>
               </span>
               <ul class="treeview-menu">
                  @if(Sentinel::hasAccess('announcement.view'))
                  <li>
            <a href="{{ url('announcement/data') }}"><i
               class="fa fa-circle-o"></i> {{trans_choice('general.view',1)}} {{trans_choice('general.announcement',1)}}
            </a></li>
            @endif
            @if(Sentinel::hasAccess('announcement.create'))
            <li><a href="{{ url('announcement/create') }}"><i
               class="fa fa-circle-o"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.announcement',1)}}
            </a></li>
            @endif

            @if(Sentinel::hasAccess('announcement.type'))
            <li><a href="{{ url('announcement/type') }}"><i
               class="fa fa-circle-o"></i>{{trans_choice('general.announcement',1)}} {{trans_choice('general.type',1)}}
            </a></li>
            @endif
			</ul>
            </a>
         </li>
         @endif
		  
		  
		 @if(Sentinel::hasAccess('dioces'))
         <li class="treeview @if(Request::is('dioces/*')) active @endif">
            <a href="#">
               <i class="fa fa-briefcase"></i> <span>{{trans_choice('general.dioces',2)}}</span>
               <span class="pull-right-container">
               <i class="fa fa-angle-left pull-right"></i>
               </span>
               <ul class="treeview-menu">
                  @if(Sentinel::hasAccess('dioces.view'))
                  <li>
            <a href="{{ url('dioces/data') }}"><i
               class="fa fa-circle-o"></i> {{trans_choice('general.view',1)}} {{trans_choice('general.dioces',1)}}
            </a></li>
            @endif
            @if(Sentinel::hasAccess('dioces.create'))
            <li><a href="{{ url('dioces/create') }}"><i
               class="fa fa-circle-o"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.dioces',1)}}
            </a></li>
            @endif
            </ul>
            </a>
         </li>
         @endif
         @if(Sentinel::hasAccess('state'))
         <li class="treeview @if(Request::is('state/*')) active @endif">
            <a href="#">
               <i class="fa fa-briefcase"></i> <span>{{trans_choice('general.state',2)}}</span>
               <span class="pull-right-container">
               <i class="fa fa-angle-left pull-right"></i>
               </span>
               <ul class="treeview-menu">
                  @if(Sentinel::hasAccess('state.view'))
                  <li>
            <a href="{{ url('state/data') }}"><i
               class="fa fa-circle-o"></i> {{trans_choice('general.view',1)}} {{trans_choice('general.state',1)}}
            </a></li>
            @endif
            @if(Sentinel::hasAccess('state.create'))
            <li><a href="{{ url('state/create') }}"><i
               class="fa fa-circle-o"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.state',1)}}
            </a></li>
            @endif
            </ul>
            </a>
         </li>
         @endif
         @if(Sentinel::hasAccess('branches'))
         <li class="treeview @if(Request::is('branch/*')) active @endif">
            <a href="#">
            <i class="fa fa-building"></i> <span>{{trans_choice('general.branch',2)}}</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
               @if(Sentinel::hasAccess('branches.view'))
               <li><a href="{{ url('branch/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.view',1)}} {{trans_choice('general.branch',2)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('branches.create'))
               <li><a href="{{ url('branch/create') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.branch',1)}}
                  </a>
               </li>
               @endif
            </ul>
         </li>
         @endif
         @if(Sentinel::hasAccess('members'))
         <li class="treeview @if(Request::is('member/*')) active @endif">
            <a href="#">
            <i class="fa fa-users"></i> <span>{{trans_choice('general.member',2)}}</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
               @if(Sentinel::hasAccess('members.view'))
               <li><a href="{{ url('member/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.view',1)}} {{trans_choice('general.member',2)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('members.create'))
               <li><a href="{{ url('member/create') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.member',1)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('members.transfer'))
               <li><a href="{{ url('member/transfer/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.view',1)}} {{trans_choice('general.member',1)}} {{trans_choice('general.transfer',1)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('members.transfer'))
               <li><a href="{{ url('member/transfer/create') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.member',1)}} {{trans_choice('general.transfer',1)}}
                  </a>
               </li>
               @endif
			 </ul>
         </li>
         @endif
         @if(Sentinel::hasAccess('members'))
         <li class="treeview @if(Request::is('soul_winning/*')) active @endif">
            <a href="#">
            <i class="fa fa-handshake-o"></i> <span>{{trans_choice('general.soul_winning',2)}}</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
               @if(Sentinel::hasAccess('members.view'))
               <li><a href="{{ url('soul_winning/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.view',1)}} {{trans_choice('general.soul_winning',2)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('members.create'))
               <li><a href="{{ url('soul_winning/create') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.soul_winning',1)}}
                  </a>
               </li>
               @endif
            </ul>
         </li>
         @endif
         <!-- Testing -->
         @if(Sentinel::hasAccess('Groups'))
         <li class="treeview @if(Request::is('groups/*')) active @endif">
            <a href="#">
            <i class="fa fa-users"></i> <span>{{trans_choice('Groups',2)}}</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
               @if(Sentinel::hasAccess('Groups.view'))
               <li><a href="{{ url('Groups/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.view',1)}} {{trans_choice('Groups',2)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('Groups.add'))
               <li><a href="{{ url('Groups/create') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.add',1)}} {{trans_choice('Groups',2)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('Groups.type'))
               <li><a href="{{ url('Groups/type') }}"><i
                  class="fa fa-circle-o"></i>Manage {{trans_choice('Groups',1)}} {{trans_choice('general.type',1)}} 
                  </a>
               </li>
               @endif
            </ul>
         </li>
         @endif
         @if(Sentinel::hasAccess('tags.view'))
         <li class=" @if(Request::is('tag/*')) active @endif"><a href="{{ url('tag/data') }}"><i
            class="fa fa-tags"></i><span>{{trans_choice('general.tag',2)}}</span>
            </a>
         </li>
         @endif
         @if(Sentinel::hasAccess('events'))
         <li class="treeview @if(Request::is('event/*')) active @endif">
            <a href="#">
            <i class="fa fa-calendar"></i> <span>{{trans_choice('general.event',2)}}</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
               @if(Sentinel::hasAccess('events.view'))
               <li><a href="{{ url('event/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.view',1)}} {{trans_choice('general.event',2)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('events.create'))
               <li><a href="{{ url('event/location/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.manage',1)}} {{trans_choice('general.location',2)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('events.create'))
               <li><a href="{{ url('event/calendar/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.manage',1)}} {{trans_choice('general.calendar',2)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('events.create'))
               <li><a href="{{ url('event/role/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.manage',1)}} {{trans_choice('general.volunteer',1)}} {{trans_choice('general.role',2)}}
                  </a>
               </li>
               @endif
            </ul>
         </li>
         @endif
  @if(Sentinel::hasAccess('pension_fund'))
         <li class="treeview @if(Request::is('pension_fund/*')) active @endif">
            <a href="#">
            <i class="fa fa-percent"></i> <span>{{trans_choice('general.pension_fund',2)}}</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
               @if(Sentinel::hasAccess('pension_fund.view'))
               <li><a href="{{ url('pension_fund/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.view',1)}} {{trans_choice('general.pension_fund',2)}}
                  </a>
               </li>
               @endif
            </ul>
         </li>
         @endif
       
		 @if(Sentinel::hasAccess('pledges'))
         <li class="treeview @if(Request::is('pledge/*')) active @endif">
            <a href="#">
            <i class="fa fa-gift"></i> <span>{{trans_choice('general.pledge',2)}}</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
               @if(Sentinel::hasAccess('pledges.view'))
               <li><a href="{{ url('pledge/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.view',1)}} {{trans_choice('general.pledge',2)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('pledges.create'))
               <li><a href="{{ url('pledge/create') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.pledge',1)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('pledges.create'))
               <li><a href="{{ url('pledge/campaign/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.manage',1)}} {{trans_choice('general.campaign',2)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('pledges_type.create'))
               <li><a href="{{ url('pledge/campaign_type/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.manage',1)}} {{trans_choice('general.campaign',2)}} {{trans_choice('general.type',2)}}
                  </a>
               </li>
               @endif						
            </ul>
         </li>
         @endif
         @if(Sentinel::hasAccess('contributions'))
         <li class="treeview @if(Request::is('contribution/*')) active @endif">
            <a href="#">
            <i class="fa fa-money"></i> <span>{{trans_choice('general.contribution',2)}}</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
               @if(Sentinel::hasAccess('contributions.view'))
               <li><a href="{{ url('contribution/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.view',1)}} {{trans_choice('general.contribution',2)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('contributions.create'))
               <li><a href="{{ url('contribution/create') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.contribution',1)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('contributions.create'))
               <li><a href="{{ url('contribution/batch/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.manage',1)}} {{trans_choice('general.batch',2)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('contributions.create'))
               <li><a href="{{ url('contribution/fund/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.manage',1)}} {{trans_choice('general.fund',2)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('contributions.create'))
               <li><a href="{{ url('contribution/payment_method/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.manage',1)}} {{trans_choice('general.payment',1)}} {{trans_choice('general.method',2)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('contributions.create'))
               <li><a href="{{ url('contribution/type/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.manage',1)}} {{trans_choice('general.contribution',1)}} {{trans_choice('general.type',2)}}
                  </a>
               </li>
               @endif
            </ul>
         </li>
         @endif
         <!-- deductions -->
         @if(Sentinel::hasAccess('deductions'))
         <li class="treeview @if(Request::is('deduction/*')) active @endif">
            <a href="#">
            <i class="fa fa-minus"></i> <span> Deductions</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
               <li><a href="{{ route('deduction.show') }}"><i
                  class="fa fa-circle-o"></i> View Deductions
                  </a>
               </li>
               <li><a href="{{ route('deduction.create') }}"><i
                  class="fa fa-circle-o"></i> Add Deductions
                  </a>
               </li>
            </ul>
         </li>
         @endif
         <!-- end of dedutions -->
         @if(Sentinel::hasAccess('follow_ups'))
         <li class="treeview @if(Request::is('follow_up/*')) active @endif">
            <a href="#">
            <i class="fa fa-comments"></i> <span>{{trans_choice('general.follow_up',2)}}</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
               @if(Sentinel::hasAccess('follow_ups.view'))
               <li><a href="{{ url('follow_up/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.view',1)}} {{trans_choice('general.follow_up',2)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('follow_ups.view'))
               <li><a href="{{ url('follow_up/data?assigned_to_id='.Sentinel::getUser()->id) }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.my',1)}} {{trans_choice('general.follow_up',2)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('follow_ups.create'))
               <li><a href="{{ url('follow_up/create') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.follow_up',1)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('follow_ups.create'))
               <li><a href="{{ url('follow_up/category/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.manage',1)}} {{trans_choice('general.follow_up',1)}} {{trans_choice('general.category',2)}}
                  </a>
               </li>
               @endif
            </ul>
         </li>
         @endif
         @if(Sentinel::hasAccess('pension'))
         <li class="treeview @if(Request::is('pension/*')) active @endif">
            <a href="#">
            <i class="fa fa-paypal"></i> <span>{{trans_choice('general.pension',1)}}</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
               @if(Sentinel::hasAccess('pension.view'))
               <li><a href="{{ url('pension/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.view',2)}} {{trans_choice('general.pension',1)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('pension.create'))
               <li><a href="{{ url('pension/create') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.pension',1)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('pension.update'))
               <li><a href="{{ url('pension/template') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.manage',1)}} {{trans_choice('general.pension',1)}} {{trans_choice('general.template',2)}}
                  </a>
               </li>
               @endif
            </ul>
         </li>
         @endif
         @if(Sentinel::hasAccess('payroll'))
         <li class="treeview @if(Request::is('payroll/*')) active @endif">
            <a href="#">
            <i class="fa fa-paypal"></i> <span>{{trans_choice('general.payroll',1)}}</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
               @if(Sentinel::hasAccess('payroll.view'))
               <li><a href="{{ url('payroll/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.view',2)}} {{trans_choice('general.payroll',1)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('payroll.create'))
               <li><a href="{{ url('payroll/create') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.payroll',1)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('payroll.update'))
               <li><a href="{{ url('payroll/template') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.manage',1)}} {{trans_choice('general.payroll',1)}} {{trans_choice('general.template',2)}}
                  </a>
               </li>
               @endif
            </ul>
         </li>
         @endif
         @if(Sentinel::hasAccess('expenses'))
         <li class="treeview @if(Request::is('expense/*')) active @endif">
            <a href="#">
            <i class="fa fa-share"></i> <span>{{trans_choice('general.expense',2)}}</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
               @if(Sentinel::hasAccess('expenses.view'))
               <li><a href="{{ url('expense/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.view',1)}} {{trans_choice('general.expense',2)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('expenses.create'))
               <li><a href="{{ url('expense/create') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.add',2)}} {{trans_choice('general.expense',1)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('expenses.create'))
               <li><a href="{{ url('expense/type/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.manage',2)}} {{trans_choice('general.expense',1)}} {{trans_choice('general.type',2)}}
                  </a>
               </li>
               @endif
            </ul>
         </li>
         @endif
         @if(Sentinel::hasAccess('other_income'))
         <li class="treeview @if(Request::is('other_income/*')) active @endif">
            <a href="#">
            <i class="fa fa-plus"></i> <span>{{trans_choice('general.other_income',2)}}</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
               @if(Sentinel::hasAccess('other_income.view'))
               <li><a href="{{ url('other_income/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.view',2)}} {{trans_choice('general.other_income',1)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('other_income.create'))
               <li><a href="{{ url('other_income/create') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.add',2)}} {{trans_choice('general.other_income',1)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('other_income.create'))
               <li><a href="{{ url('other_income/type/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.manage',2)}} {{trans_choice('general.other_income',1)}} {{trans_choice('general.type',2)}}
                  </a>
               </li>
               @endif
            </ul>
         </li>
         @endif
         @if(Sentinel::hasAccess('reports'))
         <li class="treeview @if(Request::is('report/*')) active @endif">
            <a href="#">
            <i class="fa fa-bar-chart"></i> <span>{{trans_choice('general.report',2)}}</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
               <li><a href="{{ url('report/cash_flow') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.cash_flow',2)}}
                  </a>
               </li>
               <li><a href="{{ url('report/profit_loss') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.profit_loss',2)}}
                  </a>
               </li>
               <li><a href="{{ url('report/contribution_estimate') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.contribution_estimate',2)}}
                  </a>
               </li>
               <li><a href="{{ url('report/expense_estimate') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.expense_estimate',2)}}
                  </a>
               </li>
               <li><a href="{{ url('report/contribution_report') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.contribution',1)}}
                  </a>
               </li>
               <li><a href="{{ url('report/contribution_batch') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.contribution_batch',1)}}
                  </a>
               </li>
               <li><a href="{{ url('report/contribution_summary') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.contribution_summary',1)}}
                  </a>
               </li>
               <li><a href="{{ url('report/propotional_contribution') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.propotional_contribution',1)}}
                  </a>
               </li>
               <li><a href="{{ url('report/contribution_type') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.contribution_type',1)}}
                  </a>
               </li>
            </ul>
         </li>
         @endif
         @if(Sentinel::hasAccess('communication'))
         <li class="treeview @if(Request::is('asset/*')) active @endif">
            <a href="#">
            <i class="fa fa-briefcase"></i> <span>{{trans_choice('general.asset',2)}}</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
               <li><a href="{{ url('asset/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.view',1)}} {{trans_choice('general.asset',2)}}
                  </a>
               </li>
               <li><a href="{{ url('asset/create') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.asset',1)}}
                  </a>
               </li>
               <li><a href="{{ url('asset/type/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.manage',1)}} {{trans_choice('general.asset',1)}} {{trans_choice('general.type',2)}}
                  </a>
               </li>
            </ul>
         </li>
         @endif
         @if(Sentinel::hasAccess('communication'))
         <li class="treeview @if(Request::is('communication/*')) active @endif">
            <a href="#">
            <i class="fa fa-envelope"></i> <span>{{trans_choice('general.communication',2)}}</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
               <li><a href="{{ url('communication/email') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.email',1)}}
                  </a>
               </li>
               <li><a href="{{ url('communication/sms') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.sms',2)}}
                  </a>
               </li>
               <li><a href="{{ url('sms_gateway/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.sms',1)}} {{trans_choice('general.gateway',2)}}
                  </a>
               </li>
            </ul>
         </li>
         @endif
         @if(Sentinel::hasAccess('custom_fields'))
         <li class="treeview @if(Request::is('custom_field/*')) active @endif">
            <a href="#">
            <i class="fa fa-users"></i> <span>{{trans_choice('general.custom_field',2)}}</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
               @if(Sentinel::hasAccess('custom_fields.view'))
               <li><a href="{{ url('custom_field/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.view',2)}} {{trans_choice('general.custom_field',2)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('custom_fields.create'))
               <li><a href="{{ url('custom_field/create') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.add',2)}} {{trans_choice('general.custom_field',1)}}
                  </a>
               </li>
               @endif
            </ul>
         </li>
         @endif
         @if(Sentinel::hasAccess('audit_trail'))
         <li class="@if(Request::is('audit_trail/*')) active @endif">
            <a href="{{ url('audit_trail/data') }}">
            <i class="fa fa-area-chart"></i> <span>{{trans_choice('general.audit_trail',2)}}</span>
            </a>
         </li>
         @endif
         @if(Sentinel::hasAccess('users'))
         <li class="treeview @if(Request::is('user/*')) active @endif">
            <a href="#">
            <i class="fa fa-users"></i> <span>{{trans_choice('general.user',1)}}</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
               @if(Sentinel::hasAccess('users.view'))
               <li><a href="{{ url('user/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.view',2)}} {{trans_choice('general.user',1)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('users.create'))
               <li><a href="{{ url('user/create') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.user',1)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('users.roles'))
               <li><a href="{{ url('user/role/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.user',1)}} {{trans_choice('general.role',1)}}
                  </a>
               </li>
               @endif
            </ul>
         </li>
         @endif			
         @if(Sentinel::hasAccess('fund_transfer'))
         <li class="treeview @if(Request::is('fund_transfer/*')) active @endif">
            <a href="#">
            <i class="fa fa-money"></i> <span>{{trans_choice('general.fund_transfer',1)}}</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
               @if(Sentinel::hasAccess('fund_transfer.view'))
               <li><a href="{{ url('fund_transfer/data') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.view',2)}} {{trans_choice('general.fund_transfer',1)}}
                  </a>
               </li>
               @endif
               @if(Sentinel::hasAccess('fund_transfer.create'))
               <li><a href="{{ url('fund_transfer/create') }}"><i
                  class="fa fa-circle-o"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.fund_transfer',1)}}
                  </a>
               </li>
               @endif
            </ul>
         </li>
         @endif			
         @if(Sentinel::hasAccess('settings'))
         <li class="@if(Request::is('setting/*')) active @endif">
            <a href="{{ url('setting/data') }}">
            <i class="fa fa-cog"></i> <span>{{trans_choice('general.setting',2)}}</span>
            </a>
         </li>
         @endif
      </ul>
   </section>
   <!-- /.sidebar -->
</aside>