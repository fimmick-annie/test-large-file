					<div class="row">
						<div class="col-sm-10">
							<h3><u><b>42. Lazy Pack with Instagram, no coupon site</b></u></h3>
						</div>
						<div class="col-sm-2">
							<button type="button" class="btn btn-danger btn-block" id="saveButton">Save</button>
						</div>
					</div>

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
							<small class="form-text text-muted">360 x 204, PNG</small>
						</div>
					</div>

					<!--  ----------------------------------------  -->
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

					<!--  ----------------------------------------  -->
					<hr class="m-t-3 m-b-3">
					<div class="row"><div class="col-sm-12"><h4>Offer Details Page</h4></div></div>
					<div class="row">
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Offer Details KV 01</label>
							<div class="upload-area {{ $errors->has('offer_details_kv_01') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_details_kv_01" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_details_kv_01.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('offer_details_kv_01')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x Any, JPG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Offer Details KV 02</label>
							<div class="upload-area {{ $errors->has('offer_details_kv_02') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_details_kv_02" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_details_kv_02.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('offer_details_kv_02')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x Any, JPG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Offer Details KV 03</label>
							<div class="upload-area {{ $errors->has('offer_details_kv_03') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_details_kv_03" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_details_kv_03.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('offer_details_kv_03')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x Any, JPG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Offer Details KV 04</label>
							<div class="upload-area {{ $errors->has('offer_details_kv_04') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_details_kv_04" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_details_kv_04.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('offer_details_kv_04')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x Any, JPG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Offer Details KV 05</label>
							<div class="upload-area {{ $errors->has('offer_details_kv_05') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_details_kv_05" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_details_kv_05.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('offer_details_kv_05')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x Any, JPG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Offer Details KV 06</label>
							<div class="upload-area {{ $errors->has('offer_details_kv_06') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_details_kv_06" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_details_kv_06.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('offer_details_kv_06')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x Any, JPG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Offer Details KV 07</label>
							<div class="upload-area {{ $errors->has('offer_details_kv_07') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_details_kv_07" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_details_kv_07.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('offer_details_kv_07')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x Any, JPG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Offer Details KV 08</label>
							<div class="upload-area {{ $errors->has('offer_details_kv_08') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_details_kv_08" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_details_kv_08.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('offer_details_kv_08')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x Any, JPG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Offer Details KV 09</label>
							<div class="upload-area {{ $errors->has('offer_details_kv_09') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_details_kv_09" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_details_kv_09.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('offer_details_kv_09')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x Any, JPG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Offer Details KV 10</label>
							<div class="upload-area {{ $errors->has('offer_details_kv_10') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_details_kv_10" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_details_kv_10.jpg') }}?v={{ now()->format('dHis') }}.jpg" />
							</div>
@error('offer_details_kv_10')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x Any, JPG</small>
						</div>

						<div class="col-sm-3 my-2">
							<label for="input-file-now">WhatsApp Instruction</label>
							<div class="upload-area {{ $errors->has('offer_whatsapp_instruction') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_whatsapp_instruction" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_whatsapp_instruction.png') }}?v={{ now()->format('dHis') }}.png" />
							</div>
@error('offer_whatsapp_instruction')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">800 x Any, PNG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Out of Quota Button</label>
							<div class="upload-area {{ $errors->has('offer_no_quota_button') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_no_quota_button" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_no_quota_button.png') }}?v={{ now()->format('dHis') }}.png" />
							</div>
@error('offer_no_quota_button')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">726 x 108, PNG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">WhatsApp Button</label>
							<div class="upload-area {{ $errors->has('offer_whatsapp_button') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_whatsapp_button" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_whatsapp_button.png') }}?v={{ now()->format('dHis') }}.png" />
							</div>
@error('offer_whatsapp_button')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">726 x 108, PNG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">WhatsApp Button Hightlight</label>
							<div class="upload-area {{ $errors->has('offer_whatsapp_button_highlight') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_whatsapp_button_highlight" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_whatsapp_button_highlight.png') }}?v={{ now()->format('dHis') }}.png" />
							</div>
@error('offer_whatsapp_button_highlight')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">726 x 108, PNG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Instagram Button</label>
							<div class="upload-area {{ $errors->has('offer_instagram_button') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_instagram_button" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_instagram_button.png') }}?v={{ now()->format('dHis') }}.png" />
							</div>
@error('offer_instagram_button')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">726 x 108, PNG</small>
						</div>
						<div class="col-sm-3 my-2">
							<label for="input-file-now">Instagram Button (Clicked)</label>
							<div class="upload-area {{ $errors->has('offer_instagram_button_highlight') ? 'error' : '' }}">
								<input type="file" class="dropify" name="offer_instagram_button_highlight" data-max-file-size="2M" data-default-file="{{ asset('offers/'.$offer->offer_name.'/offer_instagram_button_highlight.png') }}?v={{ now()->format('dHis') }}.png" />
							</div>
@error('offer_instagram_button_highlight')
							<span class="invalid-feedback" style="display: block;">{{ $message }}</span>
@enderror
							<small class="form-text text-muted">726 x 108, PNG</small>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<fieldset class="form-group">
								<label for="subject">Subject</label>
								<input class="form-control" type="text" value="{{ $offer->ini['offer_details']['subject']??'' }}" id="subject" name="subject" placeholder="為你推介">
							</fieldset>
						</div>
						<div class="col-lg-12">
							<fieldset class="form-group">
								<label for="subject_readmore_paragraph">Readmore Paragraph</label>
								<textarea class="form-control" id="subject_readmore_paragraph" name="subject_readmore_paragraph" rows=10>
									{{
										isset($offer->ini['offer_details']['subject_readmore_paragraph'])
										?str_replace("\\n", "\n", $offer->ini['offer_details']['subject_readmore_paragraph'])
										:""
									}}
								</textarea>
								<small class="form-text small-text-color">Support line break and HTML tags.  If you want clickable link, please use <u>&lt;a href="url" target="_blank"&gt;url&lt;/a&gt;</u></small>
							</fieldset>
						</div>
						<div class="col-lg-12">
							<fieldset class="form-group">
								<label for="highlight_subject">Highlight Subject</label>
								<input class="form-control" type="text" value="{{ $offer->ini['offer_details']['highlight_subject']??'' }}" id="highlight_subject" name="highlight_subject" placeholder="懶人包包括">
							</fieldset>
						</div>
						<div class="col-lg-12">
							<fieldset class="form-group">
								<label for="highlight_paragraph">Highlight Paragraph</label>
								<textarea class="form-control" id="highlight_paragraph" name="highlight_paragraph" rows=4>
									{{
										isset($offer->ini['offer_details']['highlight_paragraph'])
										?str_replace("\\n", "\n", $offer->ini['offer_details']['highlight_paragraph'])
										:""
									}}
								</textarea>
								<small class="form-text small-text-color">Support line break and HTML tags.  If you want clickable link, please use <u>&lt;a href="url" target="_blank"&gt;url&lt;/a&gt;</u></small>
							</fieldset>
						</div>
						<div class="col-lg-12">
							<fieldset class="form-group">
								<label for="follow_subject">Follow Subject</label>
								<input class="form-control" type="text" value="{{ $offer->ini['offer_details']['follow_subject']??'' }}" id="follow_subject" name="follow_subject" placeholder="先追蹤FB/IG﹐再領取優惠！">
							</fieldset>
						</div>
						<div class="col-lg-12">
							<fieldset class="form-group">
								<label for="instagram_link">Instagram Link</label>
								<input class="form-control" type="text" value="{{ $offer->ini['offer_details']['instagram_link']??'' }}" id="instagram_link" name="instagram_link" placeholder="https://www.instagram.com/fimmick">
							</fieldset>
						</div>
					</div>

					<!--  ----------------------------------------  -->
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

					<script>
						$(document).ready(function()  {
							CKEDITOR.replace("subject_readmore_paragraph");
							CKEDITOR.replace("highlight_paragraph");

							$("#saveButton").click(function()  {
								var basicRule = {
									rules:  {
										subject:  {minlength:3},
										subject_readmore_paragraph:  {minlength:10},

										highlight_subject:  {minlength:3},
										highlight_paragraph:  {minlength:10, alphanumeric:true},

										follow_subject:  {minlength:3},
										instagram_link:  {minlength:12},
									},
									messages: {
										subject:  {minlength:"Must consist of at least 3 characters"},
										subject_readmore_paragraph:  {minlength:"Must consist of at least 10 characters"},

										highlight_subject:  {minlength:"Must consist of at least 3 characters"},
										highlight_paragraph:  {minlength:"Must consist of at least 10 characters"},

										follow_subject:  {minlength:"Must consist of at least 3 characters"},
										instagram_link:  {minlength:"Must consist of at least 12 characters"},
									}
								};

								var form = $("#form");
								form.validate(basicRule);

								result = form.valid();
								if (result == false)  {return;}

								//  Form OK
								CKEDITOR.instances.subject_readmore_paragraph.updateElement();
								CKEDITOR.instances.highlight_paragraph.updateElement();

								var disabled = $("#form").find(":input:disabled").removeAttr("disabled");
								var formData = $("#form").serialize();
								disabled.attr("disabled", "disabled");

								showLoading();
								$.ajax({
									type: "POST",
									data: formData,
									dataType: "json",
									url: '{{ route("foso.campaigns.offer.resources.json", ["offer_code"=>$offerCode]) }}',
									success: function (result)  {
										alert(result.message);
										hideLoading();
										if (result.status != 0)  {return;}
									},
									error: function (XMLHttpRequest, textStatus, errorThrown)  {
										hideLoading();
										alert("Oops...\n#"+textStatus+": "+errorThrown);
									}
								});
							});

						});
					</script>
