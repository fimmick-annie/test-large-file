@extends('foso.layouts.default')

@section('page_title', 'WhatsApp Simulator '.$mobile)

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.campaigns.html") }}'>Campaigns</a></li>
<li><i class='fa fa-angle-right'></i> WhatsApp Simulator</li>
@endsection

@section('content')
<div id='app'></div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
<link href="/whatsAppSimulator/whatsAppSimulator.css" rel="stylesheet">
<script src="/whatsAppSimulator/whatsAppSimulator.js"></script>
<script>
    const whatsAppSimulator = WhatsAppSimulator({domId: 'app', chatbotApi: '/api/chatbot/whatsAppSimulator'})
</script>

@endsection
