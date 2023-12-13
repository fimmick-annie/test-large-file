WhatsAppSimulator = (manifest) => {
    const domId = manifest.domId;
    const chatbotApi = manifest.chatbotApi;

    const dom = document.querySelector(domId) || document.querySelector('#' + domId);
    dom.innerHTML = whatsAppSimulatorHTML;

    var deviceTime = document.querySelector('.status-bar .time');
    var messageTime = document.querySelectorAll('.message .time');

    deviceTime.innerHTML = moment().format('h:mm');

    setInterval(function () {
        deviceTime.innerHTML = moment().format('h:mm');
    }, 1000);

    for (var i = 0; i < messageTime.length; i++) {
        messageTime[i].innerHTML = moment().format('h:mm A');
    }

    /* Message */

    var form = document.querySelector('.conversation-compose');
    var conversation = document.querySelector('.conversation-container');

    // form.addEventListener('submit', newMessage);
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        // display user input
        newMessage(e);

        const input = e.target.input;
        const api = chatbotApi;

        if (input.value) {
            fetch(api, {
                method: 'post',
                headers: {
                    'Content-Type': 'application/json'
                    // 'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: JSON.stringify({ message: input.value })
            })
                .then(response => response.json())
                .then(json => {
                    console.log(json)
                    let arr = json;
                    let i = 0;
                    while(i < arr.length) {
                        if(arr[i].media && arr[i+1].message) {
                            replayMessageWithImage(arr[i].media, arr[i+1].message);
                            i+=2;
                            continue;
                        }
                        if (arr[i].media) {
                            replyMedia(arr[i].media);
                        }
                        if (arr[i].message) {
                            replyMessage(arr[i].message);
                        }
                        i++;
                    }
                })
        }
        input.value = '';


    });
    function replayMessageWithImage(url, text) {
        var element = document.createElement('div');
        element.classList.add('message', 'received');
        element.innerHTML =
            `<img src="${url}" style="width: 100%;padding-bottom: 10px;">`;
        element.innerHTML += text.replaceAll('\n', '<br/>') +
            '<span class="metadata">' +
            '<span class="time">' + moment().format('h:mm A') + '</span>' +
            '</span>';

        setTimeout(function () {
            conversation.appendChild(element);
            conversation.scrollTop = conversation.scrollHeight;
        }, 1000);
    }
    function replyMedia(url) {
        var element = document.createElement('div');
        element.classList.add('message', 'received');
        element.innerHTML =
            `<img src="${url}" style="
            width: 100%;">` +
            '<span class="metadata">' +
            '<span class="time">' + moment().format('h:mm A') + '</span>' +
            '</span>';

        setTimeout(function () {
            conversation.appendChild(element);
            conversation.scrollTop = conversation.scrollHeight;
        }, 1000);
    }
    function replyMessage(text) {
        var element = document.createElement('div');
        element.classList.add('message', 'received');
        element.innerHTML = text.replaceAll('\n', '<br/>') +
            '<span class="metadata">' +
            '<span class="time">' + moment().format('h:mm A') + '</span>' +
            '</span>';

        setTimeout(function () {
            conversation.appendChild(element);
            conversation.scrollTop = conversation.scrollHeight;
        }, 1000);

    }
    // modify to able to show image and hyper link
    function newMessage(e) {
        var input = e.target.input;
        if (input.value) {
            var message = buildMessage(input.value);
            conversation.appendChild(message);
            animateMessage(message);
        }
        conversation.scrollTop = conversation.scrollHeight;
        e.preventDefault();
    }

    function buildMessage(text) {
        var element = document.createElement('div');

        element.classList.add('message', 'sent');

        element.innerHTML = text +
            '<span class="metadata">' +
            '<span class="time">' + moment().format('h:mm A') + '</span>' +
            '<span class="tick tick-animation">' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" id="msg-dblcheck" x="2047" y="2061"><path d="M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.88a.32.32 0 0 1-.484.032l-.358-.325a.32.32 0 0 0-.484.032l-.378.48a.418.418 0 0 0 .036.54l1.32 1.267a.32.32 0 0 0 .484-.034l6.272-8.048a.366.366 0 0 0-.064-.512zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.88a.32.32 0 0 1-.484.032L1.892 7.77a.366.366 0 0 0-.516.005l-.423.433a.364.364 0 0 0 .006.514l3.255 3.185a.32.32 0 0 0 .484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z" fill="#92a58c"/></svg>' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" id="msg-dblcheck-ack" x="2063" y="2076"><path d="M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.88a.32.32 0 0 1-.484.032l-.358-.325a.32.32 0 0 0-.484.032l-.378.48a.418.418 0 0 0 .036.54l1.32 1.267a.32.32 0 0 0 .484-.034l6.272-8.048a.366.366 0 0 0-.064-.512zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.88a.32.32 0 0 1-.484.032L1.892 7.77a.366.366 0 0 0-.516.005l-.423.433a.364.364 0 0 0 .006.514l3.255 3.185a.32.32 0 0 0 .484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z" fill="#4fc3f7"/></svg>' +
            '</span>' +
            '</span>';

        return element;
    }

    function animateMessage(message) {
        setTimeout(function () {
            var tick = message.querySelector('.tick');
            tick.classList.remove('tick-animation');
        }, 500);
    }
}

const whatsAppSimulatorHTML =
    `<div id='whatsAppSimulator' class="page">
    <div class="marvel-device nexus5">
        <div class="top-bar"></div>
        <div class="sleep"></div>
        <div class="volume"></div>
        <div class="camera"></div>
        <div class="screen">
            <div class="screen-container">
                <div class="status-bar">
                    <div class="time"></div>
                    <div class="battery">
                        <i class="zmdi zmdi-battery"></i>
                    </div>
                    <div class="network">
                        <i class="zmdi zmdi-network"></i>
                    </div>
                    <div class="wifi">
                        <i class="zmdi zmdi-wifi-alt-2"></i>
                    </div>
                    <div class="star">
                        <i class="zmdi zmdi-star"></i>
                    </div>
                </div>
                <div class="chat" style='padding: 0'>
                    <div class="chat-container">
                        <div class="user-bar">
                            <div class="back">
                                <i class="zmdi zmdi-arrow-left"></i>
                            </div>
                            <div class="avatar">
                                <img src="/whatsAppSimulator/icon.png" alt="Avatar">
                            </div>
                            <div class="name">
                                <span>Kinnso</span>
                                <span class="status">Online</span>
                            </div>
                            <div class="actions more">
                                <i class="zmdi zmdi-more-vert"></i>
                            </div>
                            <div class="actions attachment">
                                <i class="zmdi zmdi-phone"></i>
                            </div>
                            <div class="actions">
                                <img src="/whatsAppSimulator/ic-action-videocall.png" />
                            </div>
                        </div>
                        <div class="conversation">
                            <div class="conversation-container">
                                <div class="message received">
                                    這是 WhatsApp 模擬器，你可以在這裡測試 Offer 的 Journey 流程。模擬器也是佔用了一個電話號碼來進行測試；若要重新測試已經領取的優惠時，是需要先行在 Offer 頁面中 Clear Whitelist Record 或輸入「 clear 」才行喔！
                                    <span class="metadata"><span class="time"></span></span>
                                </div>
                            </div>
                            <form class="conversation-compose">
                                <div class="emoji">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" id="smiley" x="3147"
                                        y="3209">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M9.153 11.603c.795 0 1.44-.88 1.44-1.962s-.645-1.96-1.44-1.96c-.795 0-1.44.88-1.44 1.96s.645 1.965 1.44 1.965zM5.95 12.965c-.027-.307-.132 5.218 6.062 5.55 6.066-.25 6.066-5.55 6.066-5.55-6.078 1.416-12.13 0-12.13 0zm11.362 1.108s-.67 1.96-5.05 1.96c-3.506 0-5.39-1.165-5.608-1.96 0 0 5.912 1.055 10.658 0zM11.804 1.01C5.61 1.01.978 6.034.978 12.23s4.826 10.76 11.02 10.76S23.02 18.424 23.02 12.23c0-6.197-5.02-11.22-11.216-11.22zM12 21.355c-5.273 0-9.38-3.886-9.38-9.16 0-5.272 3.94-9.547 9.214-9.547a9.548 9.548 0 0 1 9.548 9.548c0 5.272-4.11 9.16-9.382 9.16zm3.108-9.75c.795 0 1.44-.88 1.44-1.963s-.645-1.96-1.44-1.96c-.795 0-1.44.878-1.44 1.96s.645 1.963 1.44 1.963z"
                                            fill="#7d8489" />
                                    </svg>
                                </div>
                                <input class="input-msg" name="input" placeholder="Type a message..." autocomplete="off"
                                    autofocus>
                                <div class="photo">
                                    <img src="/whatsAppSimulator/ib-attach.png" alt="" width="25" height="25">
                                    <img src="/whatsAppSimulator/ib-camera.png" alt="" width="25" height="25">
                                </div>
                                <button class="send">
                                    <div class="circle">
                                        <i class="zmdi zmdi-mail-send"></i>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>`
