<!--  Sidebar menu  -->
<!--  https://simplelineicons.github.io/  -->
<ul class="sidebar-menu" data-widget="tree">
	<li class="header" style="background-image:url({{ asset('assets/foso/images/client.png') }}?v=1); background-size:contain; color:#ffffff;">{{ env("WHATSAPP_PREFIX") }}{{ env("BRAND_NAME") }} FOSO</li>

	<li class="treeview {{ (Request::is('foso/campaign*') ? 'active' : '') }}">
		<a href="#">
			<i class="icon-handbag"></i> <span>Campaigns</span>
			<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
		</a>
		<ul class="treeview-menu">
			<li class="{{ (Request::is('foso/campaigns/read-me') ? 'active' : '') }}">
				<a href="{{ route('foso.campaigns.readme.html') }}"><i class="fa fa-angle-right"></i> Read Me</a>
			</li>
@hasanyrole('Super-Administrator|Administrator')
			<li class="{{ ((Request::is('foso/campaigns/offer') || Request::is('foso/campaigns/offer/*')) ? 'active' : '') }}">
				<a href="{{ route('foso.campaigns.offer.html') }}"><i class="fa fa-angle-right"></i> Offer</a>
			</li>
			<li class="{{ ((Request::is('foso/campaigns/offerlist') || Request::is('foso/campaigns/offerlist/*')) ? 'active' : '') }}">
				<a href="{{ route('foso.campaigns.offerlist.management.html') }}"><i class="fa fa-angle-right"></i> Offer List Management</a>
			</li>
			<li class="{{ ((Request::is('foso/campaigns/offercollation') || Request::is('foso/campaigns/offercollation/*')) ? 'active' : '') }}">
				<a href="{{ route('foso.campaigns.offercollation.html') }}"><i class="fa fa-angle-right"></i> Offer folder</a>
			</li>
@endhasanyrole
@can("campaigns.landing.access")
			<li class="{{ ((Request::is('foso/campaigns/landing') || Request::is('foso/campaigns/landing/*')) ? 'active' : '') }}">
				<a href="{{ route('foso.campaigns.landing.keyvisuals.html') }}"><i class="fa fa-angle-right"></i> Landing</a>
			</li>
@endcan
@can("campaigns.dashboard.access")
			<li class="{{ ((Request::is('foso/campaigns/dashboard') || Request::is('foso/campaigns/dashboard/*')) ? 'active' : '') }}">
				<a href="{{ route('foso.campaigns.dashboard.index.html') }}"><i class="fa fa-angle-right"></i> Dashboard</a>
			</li>
@endcan
@can("campaigns.manage-tool.access")
			<li class="{{ ((Request::is('foso/campaigns/managetool') || Request::is('foso/campaigns/managetool/*')) ? 'active' : '') }}">
				<a href="{{ route('foso.campaigns.managetool.html') }}"><i class="fa fa-angle-right"></i> Manage Tool</a>
			</li>
@endcan
@can("campaigns.whatsapp-queue.read")
			<li class="{{ (Request::is('foso/campaigns/whatsapp/queue') ? 'active' : '') }}">
				<a href="{{ route('foso.campaigns.whatsapp.queue.html') }}"><i class="fa fa-angle-right"></i> WhatsApp Queue</a>
			</li>
@endcan
@can("campaigns.whatsapp-inbound.read")
			<li class="{{ (Request::is('foso/campaigns/whatsapp/inbound') ? 'active' : '') }}">
				<a href="{{ route('foso.campaigns.whatsapp.inbound.html') }}"><i class="fa fa-angle-right"></i> WhatsApp Inbound</a>
			</li>
@endcan
@can("campaigns.coupon-reports.access")
			<li class="{{ (Request::is('foso/campaigns/reports/all-coupons') ? 'active' : '') }}">
				<a href="{{ route('foso.campaigns.reports.allcoupons.html') }}"><i class="fa fa-angle-right"></i> Coupons Report</a>
			</li>
@endcan
			<li class="{{ (Request::is('foso/campaigns/whatsAppSimulator') ? 'active' : '') }}">
				<a href="{{ route('foso.campaigns.whatsAppSimulator.html') }}"><i class="fa fa-angle-right"></i> WhatsApp Simulator</a>
			</li>
			<li class="{{ (Request::is('foso/campaigns/faq') ? 'active' : '') }}">
				<a href="{{ route('foso.campaigns.faq.html') }}"><i class="fa fa-angle-right"></i> FAQ</a>
			</li>
		</ul>
	</li>
	<li class="treeview {{ (Request::is('foso/daily-question*') ? 'active' : '') }}">
		<a href="#">
			<i class="icon-question"></i> <span>Daily Question</span>
			<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
		</a>
		<ul class="treeview-menu">
			<li class="{{ (Request::is('foso/daily-question/report') ? 'active' : '') }}">
				<a href="{{ route('foso.dailyquestion.report.html') }}"><i class="fa fa-angle-right"></i> Report</a>
			</li>
			<li class="{{ (Request::is('foso/daily-question') ? 'active' : '') }}">
				<a href="{{ route('foso.dailyquestion.list.html') }}"><i class="fa fa-angle-right"></i> List</a>
			</li>
		</ul>
	</li>
@can("redemption.list.access")
	<li class="treeview {{ (Request::is('foso/redemption*') ? 'active' : '') }}">
		<a href="#">
			<i class="icon-badge"></i> <span>Redemption</span>
			<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
		</a>
		<ul class="treeview-menu">
			<li class="{{ (Request::is('foso/redemption*') ? 'active' : '') }}">
				<a href="{{ route('foso.redemption.html') }}"><i class="fa fa-angle-right"></i> List</a>
			</li>
			<!-- <li class="{{ (Request::is('foso/daily-question') ? 'active' : '') }}">
				<a href="{{ route('foso.dailyquestion.list.html') }}"><i class="fa fa-angle-right"></i> List</a>
			</li> -->
		</ul>
	</li>
@endcan

<!-- Kay 2022.07.14 -->
	<li class="treeview {{ (Request::is('foso/offerhunting*') ? 'active' : '') }}">
		<a href="#">
			<i class="icon-target"></i> <span>Offer Hunting</span>
			<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
		</a>
		<ul class="treeview-menu">
			<li class="{{ (Request::is('foso/offerhunting') ? 'active' : '') }}">
				<a href="{{ route('foso.offerhunting.html') }}"><i class="fa fa-angle-right"></i> List</a>
			</li>
		</ul>
	</li>
<!-- End -->
<!-- Kay 2022.11.22 -->
<li class="treeview {{ (Request::is('foso/receipthandle*') ? 'active' : '') }}">
	<a href="#">
		<i class="icon-docs"></i> <span>Receipt Upload</span>
		<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
	</a>
	<ul class="treeview-menu">
		<li class="{{ (Request::is('foso/receipthandle') ? 'active' : '') }}">
			<a href="{{ route('foso.receipthandle.html') }}"><i class="fa fa-angle-right"></i> List</a>
		</li>
	</ul>
</li>
<!-- End -->
<!-- Kay 2022.11.22 -->
<li class="treeview {{ (Request::is('foso/channel*') ? 'active' : '') }}">
	<a href="#">
		<i class="icon-screen-smartphone"></i> <span>Channel Sample</span>
		<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
	</a>
	<ul class="treeview-menu">
		<li class="{{ (Request::is('foso/channel') ? 'active' : '') }}">
			<a href="{{ route('foso.channel.html') }}"><i class="fa fa-angle-right"></i> List</a>
		</li>
	</ul>
</li>
<!-- End -->

@can("marketing.list.access")
	<li class="treeview {{ (Request::is('foso/marketing*') ? 'active' : '') }}">
		<a href="#">
			<i class="icon-directions"></i> <span>Marketing</span>
			<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
		</a>
		<ul class="treeview-menu">
			<li class="{{ (Request::is('foso/marketing') ? 'active' : '') }}">
				<a href="{{ route('foso.marketing.list.html') }}"><i class="fa fa-angle-right"></i> Marketing List</a>
			</li>
			<li class="{{ (Request::is('foso/marketing/whatsapp/blast') ? 'active' : '') }}">
				<a href="{{ route('foso.marketing.whatsapp.blast.html') }}"><i class="fa fa-angle-right"></i> WhatsApp Blasting</a>
			</li>
		</ul>
	</li>
@endcan
@can("member.list.access")
	<li class="treeview {{ (Request::is('foso/members*') ? 'active' : '') }}">
		<a href="#">
			<i class="icon-people"></i> <span>Members</span>
			<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
		</a>
		<ul class="treeview-menu">
			<li class="{{ (Request::is('foso/members') ? 'active' : '') }}">
				<a href="{{ route('foso.members.search.html') }}"><i class="fa fa-angle-right"></i> Search</a>
			</li>
		</ul>
	</li>
@endcan
@can("reporting.list.access")
	<li class="treeview {{ (Request::is('foso/reporting*') ? 'active' : '') }}">
		<a href="#">
			<i class="icon-paper-plane"></i> <span>Report</span>
			<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
		</a>
		<ul class="treeview-menu">
			<li class="{{ (Request::is('foso/reporting/point*') ? 'active' : '') }}">
				<a href="{{ route('foso.reporting.point.html') }}"><i class="fa fa-angle-right"></i> Point</a>
			</li>
		</ul>
	</li>
@endcan
<li class="treeview {{ (Request::is('foso/app*') ? 'active' : '') }}">
	<a href="#">
		<i class="icon-people"></i> <span>Mobile App</span>
		<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
	</a>
	<ul class="treeview-menu">
		<li class="{{ (Request::is('foso/app/user') ? 'active' : '') }}">
			<a href="{{ route('foso.app.user.html') }}"><i class="fa fa-angle-right"></i> App User</a>
		</li>
		<li class="{{ (Request::is('foso/app/scan-log') ? 'active' : '') }}">
			<a href="{{ route('foso.app.scanlog.html') }}"><i class="fa fa-angle-right"></i> Scan Log</a>
		</li>
	</ul>
</li>
<li class="treeview {{ (Request::is('foso/thirdparty*') ? 'active' : '') }}">
	<a href="#">
		<i class="icon-puzzle"></i> <span>Third Party Event</span>
		<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
	</a>
	<ul class="treeview-menu">
		<li class="{{ (Request::is('foso/thirdparty/eventlist') ? 'active' : '') }}">
			<a href="{{ route('foso.thirdparty.eventlist.html') }}"><i class="fa fa-angle-right"></i> Data Collection</a>
		</li>
		<!-- <li class="{{ (Request::is('foso/app/scan-log') ? 'active' : '') }}">
			<a href="{{ route('foso.app.scanlog.html') }}"><i class="fa fa-angle-right"></i> Scan Log</a>
		</li> -->
	</ul>
</li>
@can("banner.list.access")
<li class="treeview {{ (Request::is('foso/banner*') ? 'active' : '') }}">
	<a href="#">
		<i class="icon-picture"></i> <span>Banner</span>
		<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
	</a>
	<ul class="treeview-menu">
		<li class="{{ (Request::is('foso/banner/bannerlist') ? 'active' : '') }}">
			<a href="{{ route('foso.banner.bannerlist.html') }}"><i class="fa fa-angle-right"></i> Landing</a>
		</li>
	</ul>
</li>
@endcan

<!-- Kay 2022.10.25 -->
<li class="treeview {{ (Request::is('foso/activitylog*') ? 'active' : '') }}">
	<a href="#">
		<i class="icon-info"></i> <span>Activity log</span>
		<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
	</a>
	<ul class="treeview-menu">
		<li class="{{ (Request::is('foso/activitylog/list') ? 'active' : '') }}">
			<a href="{{ route('foso.activitylog.list.html') }}"><i class="fa fa-angle-right"></i> List</a>
		</li>
	</ul>
</li>
<!-- End -->
@hasanyrole('Super-Administrator|Administrator')
	<li class="header">Administration</li>
	<li class="treeview {{ (Request::is('foso/users*') || Request::is('foso/roles*') || Request::is('foso/permissions*') ? 'active' : '') }}">
		<a href="#">
			<i class="icon-people"></i>
			<span>User Administration</span>
			<span class="pull-right-container">
				<i class="fa fa-angle-left pull-right"></i>
			</span>
		</a>
		<ul class="treeview-menu">
			<li class="{{ (Request::is('foso/users*') ? 'active' : '') }}">
				<a href="{{route('foso.users.index')}}">
					<i class="icon-people"></i>
					<i class="fa fa-angle-right"></i> FOSO Users
				</a>
			</li>
			<li class="{{ (Request::is('foso/roles*') ? 'active' : '') }}">
				<a href="{{route('foso.roles.index')}}">
					<i class="fa fa-id-card-o"></i>
					<i class="fa fa-angle-right"></i> Roles Management
				</a>
			</li>
			<li class="{{ (Request::is('foso/permissions*') ? 'active' : '') }}">
				<a href="{{route('foso.permissions.index')}}">
					<i class="icon-docs"></i>
					<i class="fa fa-angle-right"></i> Permissions
				</a>
			</li>
		</ul>
	</li>
@endhasanyrole

</ul>