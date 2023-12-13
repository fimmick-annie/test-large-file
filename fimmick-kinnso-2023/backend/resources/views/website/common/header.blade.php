@if ($_SERVER["SERVER_NAME"] != env('DOMAIN_PRODUCTION') && $_SERVER["SERVER_NAME"] != "kinnso.com")
			<div class="developer" style="position:fixed;z-index:9999999;opacity:.75;top:0;left:0;width:100%;padding:8px;background-color:#ff0000;">
				<h2 style="color:#ffffff;font-size:0.7rem;text-align:center;">Non Production Site</h2>
			</div>

@endif
			<div id="loading" style="position:fixed;z-index:9999990;top:0;left:0;width:100%;height:100%;display:none;align-items:center;justify-content:center;background-color:rgba(0,0,0,.8);">
				<img src="{{ asset('offers/common/loading.gif') }}?v=1" alt="loading" style="max-width:200px;" />
			</div>
