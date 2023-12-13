					<div class="row"><div class="col-sm-12"><h3><u><b>#4 Live Streaming Link</b> Template Resources Page</u></h3></div></div>
					<br>

					<div class="row"><div class="col-sm-12"><h4>Offer Thumbnail at Listing Page</h4></div></div>
					<div class="row">
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Thumbnail</label>
							<div class="upload-area {{ $errors->has('offer_thumbnail') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_thumbnail" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_thumbnail.png') }}?v={{ now()->format('dHis') }}.png" />
							</div>
@error('offer_thumbnail')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">240 x 240, PNG</small>
						</div>
					</div>

					<hr class="m-t-3 m-b-3">
					<div class="row"><div class="col-sm-12"><h4>Offer Coming Soon Page</h4></div></div>
					<div class="row">
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Coming Soon KV</label>
							<div class="upload-area {{ $errors->has('offer_comingsoon_kv') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_comingsoon_kv" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_comingsoon_kv.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('offer_comingsoon_kv')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x Any, JPG</small>
						</div>
					</div>

					<hr class="m-t-3 m-b-3">
					<div class="row"><div class="col-sm-12"><h4>Offer Details Page</h4></div></div>
					<div class="row">
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Offer Details KV</label>
							<div class="upload-area {{ $errors->has('offer_details_kv') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_details_kv" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_details_kv.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('offer_details_kv')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x Any, JPG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">WhatsApp Button</label>
							<div class="upload-area {{ $errors->has('offer_whatsapp_button') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_whatsapp_button" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_whatsapp_button.png') }}?v={{ now()->format('dHis') }}.png" />
							</div>
@error('offer_thankyou_whatsapp_button')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">726 x 108, PNG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Out of Quota Button</label>
							<div class="upload-area {{ $errors->has('offer_no_quota_button') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_no_quota_button" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_no_quota_button.png') }}?v={{ now()->format('dHis') }}.png" />
							</div>
@error('offer_thankyou_whatsapp_button')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">726 x 108, PNG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Thank You KV</label>
							<div class="upload-area {{ $errors->has('offer_thankyou_kv') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_thankyou_kv" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_thankyou_kv.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('offer_thankyou_kv')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x Any, JPG</small>
						</div>
					</div>

					<hr class="m-t-3 m-b-3">
					<div class="row"><div class="col-sm-12"><h4>Offer Expiry Page</h4></div></div>
					<div class="row">
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Expired KV</label>
							<div class="upload-area {{ $errors->has('offer_expired_kv') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_expired_kv" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_expired_kv.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('offer_expired_kv')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x Any, JPG</small>
						</div>
					</div>
