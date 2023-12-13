<!-- offer -->
<script id="offer-template" type="text/x-handlebars-template">
    @{{#each offers}}
    <div class="offer-card-wrapper" data-id="@{{id}}">
        <div class="label-wrapper">
            @{{#each tags}}
                @{{#ifEquals this "hot"}}
            <div class="label hot-label"></div>
                @{{/ifEquals}}
                @{{#ifEquals this "new"}}
            <div class="label new-label"></div>
                @{{/ifEquals}}
                @{{#ifEquals this "push"}}
            <div class="label kinso-label"></div>
                @{{/ifEquals}}
                @{{#ifEquals this "less"}}
            <div class="label litter-label"></div>
                @{{/ifEquals}}
                @{{#ifEquals this "soldout"}}
            <div class="label soldout-label"></div>
                @{{/ifEquals}}
                @{{#ifEquals this "end"}}
            <div class="label end-label"></div>
                @{{/ifEquals}}
            @{{/each}}
        </div>
        @{{#url}}<a href="@{{this}}">@{{/url}}
            <div class="offer-card">
                <div class="image" style="--background-image:url('@{{key-visual}}')"></div>
                <div class="tagging">
                    <ul>
                        @{{#each labels}}
                        <li data-text="@{{text}}" style="color:@{{text-color}}"></li>
                        @{{/each}}
                    </ul>
                </div>
                <div class="title">@{{title}}</div>
            </div>
        @{{#url}}</a>@{{/url}}
    </div>
    @{{else}}
    <div class="no-result">
        暫時未有相關優惠
        <div class="sleeping-bear"></div>
    </div>
    @{{/each}}
    @{{#if is-hot-offer}}
    <div class="button show-all-button">所有優惠</div>
    @{{/if}}
</script>
<script>
    let __offerList = (function(window, document, undefined) {
        const offerTemplate = Handlebars.compile(document.getElementById('offer-template').innerHTML);

        function init(eleId, data) {
            let offerListEle = document.getElementById(eleId);
            let html = offerTemplate(data);
			if (offerListEle) offerListEle.innerHTML = html;
            if (data.offers instanceof Array && data.offers.length > 0) {
                offerListEle.classList.remove('no-result');
            } else {
                offerListEle.classList.add('no-result');
            }
            if (data['is-hot-offer'] === true) {
                {{--
                /* if (data.offers instanceof Array && data.offers.length <= 4) {
                    offerListEle.classList.add('show-all');
                } else {
                    let showAllButtonEle = offerListEle.getElementsByClassName('show-all-button')[0];
                    if (showAllButtonEle) {
                        showAllButtonEle.onclick = () => {
                            offerListEle.classList.add('show-all');
                        }
                    }
                } */
                --}}
                let showAllButtonEle = offerListEle.getElementsByClassName('show-all-button')[0];
                if (showAllButtonEle) {
                    showAllButtonEle.onclick = () => {
                        let redirectUrl = '{{ route("campaign.offer.listing.html") }}#offer-listing';

                        analytics.track('click-moreoffer-button', {
                            device: '{{ $device }}',
                            os: '{{ $operatingSystem }}',
                            url: redirectUrl,
                            ip: '{{ $ipAddress }}',
                            userAgent: '{{ $userAgent }}',
                            referrer: window.location.href
                        }).finally(() => {
                            window.location.href = redirectUrl;
                        });
                    }
                }
            }
        }

        return {
            init
        }
    })(window, document, undefined);
</script>