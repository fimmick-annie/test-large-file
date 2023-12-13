		<meta charset="utf-8">
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0" />

		<meta name="author" content="Kinnso" />
		<meta name="copyright" content="Copyright Fimmick Limited" />

		<meta name="description" content="著數？唔使自己周圍搲嘅，有 Kinnso，著數自動送上門！" />
		<meta name="keywords" content="Kinnso,著數,吃,喝,玩,樂" />

		<meta itemprop="name" content="Kinnso" />
		<meta itemprop="image" content="{{ asset('offers/common/og_image.jpg') }}?v=2" />
		<meta itemprop="description" content="著數？唔使自己周圍搲嘅，有 Kinnso，著數自動送上門！" />

		<meta property="og:title" content="Kinnso" />
		<meta property="og:type" content="website" />
		<meta property="og:url" content="https://www.kinnso.com/" />
		<meta property="og:image" content="{{ asset('offers/common/og_image.jpg') }}?v=2" />
		<meta property="og:description" content="著數？唔使自己周圍搲嘅，有 Kinnso，著數自動送上門！" />

		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Kinnso</title>

		<link rel="shortcut icon" href="{{ asset('apple-touch-icon.png') }}?v=1" />
		<link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}?v=1" />

		<link rel="stylesheet" href="{{ asset('website/website.css') }}?v=1" />

@if (isset($facebookPixel))
@foreach($facebookPixel as $code)
@if ($code != "")

		<!-- Facebook Pixel Code -->
		<script>
			!function(f,b,e,v,n,t,s)
			{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
			n.callMethod.apply(n,arguments):n.queue.push(arguments)};
			if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
			n.queue=[];t=b.createElement(e);t.async=!0;
			t.src=v;s=b.getElementsByTagName(e)[0];
			s.parentNode.insertBefore(t,s)}(window, document,'script',
			'https://connect.facebook.net/en_US/fbevents.js');
			fbq('init', '{{ $code }}');
			fbq('track', 'PageView');
		</script>
		<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{ $code }}&ev=PageView&noscript=1"/></noscript>
		<!-- End Facebook Pixel Code -->
@endif
@endforeach
@endif
@if (isset($googleAnalytics))
@foreach($googleAnalytics as $code)
@if ($code != "")

		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id={{ $code }}"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());
			gtag('config', '{{ $code }}');
		</script>
@endif
@endforeach
@endif
@if (isset($gtm))
@foreach($gtm as $code)
@if ($code != "")

		<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','{{ $code }}');</script>
		<!-- End Google Tag Manager -->
@endif
@endforeach
@endif

		<!-- Google Tag Manager -->
		<!-- Hard code installed -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-KMS5KJR');</script>
		<!-- End Google Tag Manager -->

		<!-- Google Tag Manager -->
		<!-- Hard code installed for gtm server tracking -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start': 
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0], 
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f); 
		})(window,document,'script','dataLayer','GTM-TTZ8KCP');</script>
		<!-- End Google Tag Manager -->

		<!--  Lifesight  -->
		<script type="text/javascript">
		(()=>{ var w=window; var p=w.Personica;
			var id='7208b98a-7b41-4908-bebe-6dbac5049c94';
			window.personicaSetting={dataId:id};
			if(typeof p==="function"){}else{
				var d=document;
				var i=function(){i.c(arguments);};
				i.q=[];i.c=function(args){i.q.push(args);};
				w.Personica=i; var l=function(){
					var s=d.createElement('script');
					s.type='text/javascript'; s.async=true; s.src='https://app.lifesight.io/personica.js';
					var x=d.getElementsByTagName('script')[0];
					x.parentNode.insertBefore(s,x);
				};
				if(w.attachEvent){w.attachEvent('onload',l);}
				else{w.addEventListener('load',l,false);}
			}
		})();
		</script>
		<!--  End Lifesight  -->
