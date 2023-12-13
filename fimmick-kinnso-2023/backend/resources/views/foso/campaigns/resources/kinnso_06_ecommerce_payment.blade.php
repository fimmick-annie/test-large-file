					<div class="row"><div class="col-sm-12"><h3><u><b>#6 eCommerce Payment</b> Template Resources Page</u></h3></div></div>
					<br>

					<div class="row"><div class="col-sm-12"><h4>Offer Thumbnail at List Page</h4></div></div>
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
							<label for="input-file-now">Button Product</label>
							<div class="upload-area {{ $errors->has('button_product') ? 'error' : '' }}">
								<input type="file" class="dropify" name="button_product" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/button_product.png') }}?v={{ now()->format('dHis') }}.png" />
							</div>
@error('button_product')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">400 x 250, PNG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Button Register</label>
							<div class="upload-area {{ $errors->has('button_register') ? 'error' : '' }}">
								<input type="file" class="dropify" name="button_register" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/button_register.png') }}?v={{ now()->format('dHis') }}.png" />
							</div>
@error('button_register')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">400 x 250, PNG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Details KV</label>
							<div class="upload-area {{ $errors->has('offer_details_kv') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_details_kv" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_details_kv.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('offer_details_kv')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x 800, JPG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Details Coupon</label>
							<div class="upload-area {{ $errors->has('offer_details_coupon') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_details_coupon" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_details_coupon.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('offer_details_coupon')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x 800, JPG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Icon WhatsApp</label>
							<div class="upload-area {{ $errors->has('icon_whatsapp') ? 'error' : '' }}">
								<input type="file" class="dropify" name="icon_whatsapp" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/icon_whatsapp.png') }}?v={{ now()->format('dHis') }}.png" />
							</div>
@error('icon_whatsapp')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">190 x 60, PNG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Details Product 00001</label>
							<div class="upload-area {{ $errors->has('offer_details_product_00001') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_details_product_00001" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_details_product_00001.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('offer_details_product_00001')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x Any, JPG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Details Product 00002</label>
							<div class="upload-area {{ $errors->has('offer_details_product_00002') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_details_product_00002" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_details_product_00002.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('offer_details_product_00002')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x Any, JPG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Details Product 00003</label>
							<div class="upload-area {{ $errors->has('offer_details_product_00003') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_details_product_00003" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_details_product_00003.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('offer_details_product_00003')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x Any, JPG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Details Product 00004</label>
							<div class="upload-area {{ $errors->has('offer_details_product_00004') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_details_product_00004" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_details_product_00004.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('offer_details_product_00004')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x Any, JPG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Details Product 00005</label>
							<div class="upload-area {{ $errors->has('offer_details_product_00005') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_details_product_00005" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_details_product_00005.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('offer_details_product_00005')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x Any, JPG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Details Product 00006</label>
							<div class="upload-area {{ $errors->has('offer_details_product_00006') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_details_product_00006" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_details_product_00006.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('offer_details_product_00006')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x Any, JPG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Details Product 00007</label>
							<div class="upload-area {{ $errors->has('offer_details_product_00007') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_details_product_00007" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_details_product_00007.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('offer_details_product_00007')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x Any, JPG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Details Product 00008</label>
							<div class="upload-area {{ $errors->has('offer_details_product_00008') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_details_product_00008" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_details_product_00008.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('offer_details_product_00008')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x Any, JPG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Details Product 00009</label>
							<div class="upload-area {{ $errors->has('offer_details_product_00009') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_details_product_00009" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_details_product_00009.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('offer_details_product_00009')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x Any, JPG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Details Product 00010</label>
							<div class="upload-area {{ $errors->has('offer_details_product_00010') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_details_product_00010" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_details_product_00010.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('offer_details_product_00010')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x Any, JPG</small>
						</div>
					</div>

					<hr class="m-t-3 m-b-3">
					<div class="row"><div class="col-sm-12"><h4>Offer Thank You Page</h4></div></div>
					<div class="row">
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Info Image</label>
							<div class="upload-area {{ $errors->has('offer_thankyou_info') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_thankyou_info" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_thankyou_info.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('offer_thankyou_info')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x 300, JPG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">WhatsApp Button</label>
							<div class="upload-area {{ $errors->has('offer_thankyou_whatsapp_button') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_thankyou_whatsapp_button" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_thankyou_whatsapp_button.png') }}?v={{ now()->format('dHis') }}.png" />
							</div>
@error('offer_thankyou_whatsapp_button')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">600 x 300, PNG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Button Background</label>
							<div class="upload-area {{ $errors->has('offer_thankyou_button_background') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_thankyou_button_background" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_thankyou_button_background.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('offer_thankyou_button_background')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x 400, JPG</small>
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

					<hr class="m-t-3 m-b-3">
					<div class="row"><div class="col-sm-12"><h4>Coupon Coming Soon Page</h4></div></div>
					<div class="row">
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Coming Soon KV</label>
							<div class="upload-area {{ $errors->has('coupon_comingsoon_kv') ? 'error' : '' }}">
								<input type="file" class="dropify" name="coupon_comingsoon_kv" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/coupon_comingsoon_kv.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('coupon_comingsoon_kv')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x Any, JPG</small>
						</div>
					</div>

					<hr class="m-t-3 m-b-3">
					<div class="row"><div class="col-sm-12"><h4>Coupon Countdown Page</h4></div></div>
					<div class="row">
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Countdown KV</label>
							<div class="upload-area {{ $errors->has('coupon_countdown_kv') ? 'error' : '' }}">
								<input type="file" class="dropify" name="coupon_countdown_kv" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/coupon_countdown_kv.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('coupon_countdown_kv')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x 700, JPG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Countdown Message</label>
							<div class="upload-area {{ $errors->has('coupon_countdown_message') ? 'error' : '' }}">
								<input type="file" class="dropify" name="coupon_countdown_message" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/coupon_countdown_message.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('coupon_countdown_message')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x 480, JPG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Countdown Product 00001</label>
							<div class="upload-area {{ $errors->has('coupon_countdown_product_00001') ? 'error' : '' }}">
								<input type="file" class="dropify" name="coupon_countdown_product_00001" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/coupon_countdown_product_00001.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('coupon_countdown_product_00001')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x Any, JPG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Countdown Product 00002</label>
							<div class="upload-area {{ $errors->has('coupon_countdown_product_00002') ? 'error' : '' }}">
								<input type="file" class="dropify" name="coupon_countdown_product_00002" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/coupon_countdown_product_00002.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('coupon_countdown_product_00002')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x Any, JPG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Countdown Product 00003</label>
							<div class="upload-area {{ $errors->has('coupon_countdown_product_00003') ? 'error' : '' }}">
								<input type="file" class="dropify" name="coupon_countdown_product_00003" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/coupon_countdown_product_00003.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('coupon_countdown_product_00003')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x Any, JPG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Countdown Product 00004</label>
							<div class="upload-area {{ $errors->has('coupon_countdown_product_00004') ? 'error' : '' }}">
								<input type="file" class="dropify" name="coupon_countdown_product_00004" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/coupon_countdown_product_00004.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('coupon_countdown_product_00004')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x Any, JPG</small>
						</div>
					</div>

					<hr class="m-t-3 m-b-3">
					<div class="row"><div class="col-sm-12"><h4>Coupon Thank You Page</h4></div></div>
					<div class="row">
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Thank You KV</label>
							<div class="upload-area {{ $errors->has('coupon_thankyou_kv') ? 'error' : '' }}">
								<input type="file" class="dropify" name="coupon_thankyou_kv" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/coupon_thankyou_kv.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('coupon_thankyou_kv')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x 1000, JPG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Thank You Message</label>
							<div class="upload-area {{ $errors->has('coupon_thankyou_message') ? 'error' : '' }}">
								<input type="file" class="dropify" name="coupon_thankyou_message" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/coupon_thankyou_message.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('coupon_thankyou_message')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x 480, JPG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Thank You Product</label>
							<div class="upload-area {{ $errors->has('coupon_thankyou_product') ? 'error' : '' }}">
								<input type="file" class="dropify" name="coupon_thankyou_product" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/coupon_thankyou_product.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('coupon_thankyou_product')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x 900, JPG</small>
						</div>
					</div>

					<hr class="m-t-3 m-b-3">
					<div class="row"><div class="col-sm-12"><h4>Coupon Expiry Page</h4></div></div>
					<div class="row">
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Expired KV</label>
							<div class="upload-area {{ $errors->has('coupon_expired_kv') ? 'error' : '' }}">
								<input type="file" class="dropify" name="coupon_expired_kv" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/coupon_expired_kv.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('coupon_expired_kv')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x Any, JPG</small>
						</div>
					</div>

					<hr class="m-t-3 m-b-3">
					<div class="row"><div class="col-sm-12"><h4>Common</h4></div></div>
					<div class="row">
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Confirm Button</label>
							<div class="upload-area {{ $errors->has('button_confirm') ? 'error' : '' }}">
								<input type="file" class="dropify" name="button_confirm" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/button_confirm.png') }}?v={{ now()->format('dHis') }}.png" />
							</div>
@error('button_confirm')
								<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x 146, PNG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Submit Button</label>
							<div class="upload-area {{ $errors->has('button_submit') ? 'error' : '' }}">
								<input type="file" class="dropify" name="button_submit" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/button_submit.png') }}?v={{ now()->format('dHis') }}.png" />
							</div>
@error('button_submit')
								<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x 146, PNG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Share Button</label>
							<div class="upload-area {{ $errors->has('button_share') ? 'error' : '' }}">
								<input type="file" class="dropify" name="button_share" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/button_share.png') }}?v={{ now()->format('dHis') }}.png" />
							</div>
@error('button_share')
								<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x 146, PNG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Back Button</label>
							<div class="upload-area {{ $errors->has('button_back') ? 'error' : '' }}">
								<input type="file" class="dropify" name="button_back" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/button_back.png') }}?v={{ now()->format('dHis') }}.png" />
							</div>
@error('button_back')
								<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x 146, PNG</small>
						</div>
					</div>
