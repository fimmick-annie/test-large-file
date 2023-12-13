<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<url><loc>https://www.kinnso.ai/</loc></url>
	<url><loc>https://www.kinnso.ai/about-us</loc></url>
	<url><loc>https://www.kinnso.ai/partnership</loc></url>
	<url><loc>https://www.kinnso.ai/privacy</loc></url>
	<url><loc>https://www.kinnso.ai/terms-and-conditions</loc></url>
@foreach ($offerArray as $offer)

@if (strtotime($offer->end_at) <= time())
	<url>
		<loc>{{ route("campaign.offer.details.html", [$offer["offer_code"]]) }}/expired</loc>
		<lastmod>{{ \Carbon\Carbon::parse($offer["updated_at"])->toW3cString() }}</lastmod>
		<changefreq>daily</changefreq>
	</url>
@else
	<url>
		<loc>{{ route("campaign.offer.details.html", [$offer["offer_code"]]) }}</loc>
		<lastmod>{{ \Carbon\Carbon::parse($offer["updated_at"])->toW3cString() }}</lastmod>
		<changefreq>daily</changefreq>
	</url>
@endif

@endforeach
</urlset>