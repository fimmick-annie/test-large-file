<!-- hot-topic -->
<script id="hot-topic-template" type="text/x-handlebars-template">
    @{{#each topics}}
    <a href="javascript:void(0)" class="hot-topic" data-text="@{{text}}" style="--color:@{{text-color}}"></a>
    @{{/each}}
</script>
<!-- offer-filter -->
<script id="offer-filter-template" type="text/x-handlebars-template">
    <div class="filter-category" data-text="好去處">
        <div class="filter-sub-category-list">
            <a href="javascript:void(0)" class="filter-sub-category" data-text="一日遊"></a>
            <a href="javascript:void(0)" class="filter-sub-category" data-text="情侶"></a>
            <a href="javascript:void(0)" class="filter-sub-category" data-text="親子"></a>
            <a href="javascript:void(0)" class="filter-sub-category" data-text="戶外活動"></a>
            <a href="javascript:void(0)" class="filter-sub-category" data-text="一日遊"></a>
        </div>
    </div>
</script>
<!-- offer-category -->
<script id="offer-category-template" type="text/x-handlebars-template">
    @{{#each categories}}
    <a href="javascript:void(0)" class="category" data-text="@{{text}}" style="--background-image:url('@{{icon}}');--hover-background-image:url('@{{highlight}}"></a>
    @{{/each}}
</script>
<script>
    let __hotTopicAndFilter = (function(window, document, undefined) {
        const hotTopicListEle = document.getElementById('hot-topic-list');
		const offerFilterListEle = document.getElementById('offer-filter-list');
        const offerCategoryListEle = document.getElementById('offer-category-list');
        const hotTopicTemplate = Handlebars.compile(document.getElementById('hot-topic-template').innerHTML);
		const offerFilterTemplate = Handlebars.compile(document.getElementById('offer-filter-template').innerHTML);
        const offerCategoryTemplate = Handlebars.compile(document.getElementById('offer-category-template').innerHTML);
        const filterMasKEle = document.getElementById('filter-mask');
		const filterButtonEle = document.getElementById('filter-button');
		const filterWrapper = filterButtonEle.querySelector('.filter-wrapper');
        const filterCloseButtonEle = filterWrapper.querySelector('.close-button');
		const filterSearchButtonEle = filterWrapper.querySelector('.search-button');
		const filterResetButtonEle = filterWrapper.querySelector('.reset-button');

        function initHotTopic(data) {
            let html = hotTopicTemplate({"topics": data});
			if (hotTopicListEle) hotTopicListEle.innerHTML = html;

            let filter = hotTopicListEle.dataset.filter;
            let eles = hotTopicListEle.querySelectorAll('.hot-topic');
            eles.forEach(function(ele) {
                ele.onclick = function() {
                    let text = ele.dataset.text || undefined;
                    if (text) {
                        let redirectUrl = '{{ route("campaign.offer.filter.html") }}?filter='+encodeURI(text);
						try  {

							analytics.track('click-hottpoic-button', {
								device: '{{ $device }}',
								os: '{{ $operatingSystem }}',
								url: redirectUrl,
								ip: '{{ $ipAddress }}',
								userAgent: '{{ $userAgent }}',
								referrer: window.location.href,
								hottopic: text
							}).finally(() => {
								window.location.href = redirectUrl;
							});

						}  catch (error)  {
							window.location.href = redirectUrl;
						}
                    }
                }
                if (filter && filter == ele.dataset.text) {
                    ele.classList.add('active');
                }
            });
        }

        function initFilter(data) {
            let html = offerFilterTemplate({"topics": data});
			if (offerFilterListEle) offerFilterListEle.innerHTML = html;
        }

        function initOfferCategory(data) {
            let html = offerCategoryTemplate({"categories": data});
			if (offerCategoryListEle) offerCategoryListEle.innerHTML = html;

            let filter = offerCategoryListEle.dataset.filter;
            let eles = offerCategoryListEle.querySelectorAll('.category');
            eles.forEach(function(ele) {
                ele.onclick = function() {
                    let text = ele.dataset.text || undefined;
                    if (text) {
                        let redirectUrl = '{{ route("campaign.offer.filter.html") }}?category='+encodeURI(text);
						try  {

							analytics.track('click-category-button', {
								device: '{{ $device }}',
								os: '{{ $operatingSystem }}',
								url: redirectUrl,
								ip: '{{ $ipAddress }}',
								userAgent: '{{ $userAgent }}',
								referrer: window.location.href,
								category: text
							}).finally(() => {
								window.location.href = redirectUrl;
							});
						}  catch (error)  {
							window.location.href = redirectUrl;
						}
                    }
                }
                if (filter && filter == ele.dataset.text) {
                    ele.classList.add('active');
                }
            });
        }

        filterButtonEle.onclick = () => {
			filterButtonEle.classList.toggle('active');
            if (filterButtonEle.classList.contains('active')) {
                filterMasKEle.style.display = 'block';

                analytics.track('click-filter-button', {
                    device: '{{ $device }}',
                    os: '{{ $operatingSystem }}',
                    url: window.location.href,
                    ip: '{{ $ipAddress }}',
                    userAgent: '{{ $userAgent }}',
                    referrer: window.location.href
                });
            } else {
                filterMasKEle.style.display = 'none';
            }
		}
		filterWrapper.onclick = (event) => {
			event.stopPropagation();
		}
		filterSearchButtonEle.onclick = () => {
			let eles = offerFilterListEle.querySelectorAll('.filter-sub-category.active');
            let items = [];
            eles.forEach(function(ele) {
                let text = ele.dataset.text || undefined;
                if (text) {
                    items.push(encodeURI(text));
                }
            });
            if (items.length > 0) {
                let filterQuery = '?filter[]='+items.join('&filter[]=');
                let redirectUrl = '{{ route("campaign.offer.filter.html") }}'+filterQuery;
				try  {

					analytics.track('click-searchfilter-button', {
						device: '{{ $device }}',
						os: '{{ $operatingSystem }}',
						url: redirectUrl,
						ip: '{{ $ipAddress }}',
						userAgent: '{{ $userAgent }}',
						referrer: window.location.href,
						search: items.join(',')
					}).finally(() => {
						window.location.href = redirectUrl;
					});

				}  catch (error)  {
					window.location.href = redirectUrl;
				}
            }
		}
		filterResetButtonEle.onclick = () => {
			filterWrapper.querySelectorAll('a.filter-sub-category').forEach(ele => {
                ele.classList.remove('active');
            });

            analytics.track('click-resetfilter-button', {
                device: '{{ $device }}',
                os: '{{ $operatingSystem }}',
                url: window.location.href,
                ip: '{{ $ipAddress }}',
                userAgent: '{{ $userAgent }}',
                referrer: window.location.href
            });
		}
        filterCloseButtonEle.onclick = () => {
            filterButtonEle.classList.remove('active');
            filterMasKEle.style.display = 'none';
        }

        //---------------------
        let selectedFilters = [];
        let selectedFilterCount = 0;
        try {
            selectedFilters = JSON.parse(offerFilterListEle.dataset.filter);
            if (!(selectedFilters instanceof Array))
                selectedFilters = [];
        } catch(error) {}
        const filterSubCategoryEles = offerFilterListEle.querySelectorAll('.filter-sub-category');
        filterSubCategoryEles.forEach(function(ele) {
            let text = ele.dataset.text || undefined;
			ele.onclick = function() {
				this.classList.toggle('active');
			}
            if (text) {
                selectedFilters.some(function(filter) {
                    if (filter == text) {
                        ele.classList.add('active');
                        selectedFilterCount++;
                        return true;
                    }
                });
            }
		});
        if (selectedFilterCount > 0) {
            filterButtonEle.dataset.count = selectedFilterCount;
        }

        return {
            initHotTopic,
            initFilter,
            initOfferCategory
        }
    })(window, document, undefined);
</script>