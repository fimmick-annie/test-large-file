(1, '2021-07-21 00:00:00', '2021-07-21 00:00:00', NULL, 20, 100, 'issue-coupon', 300, '{\"nextNode\": \"coupon-message\", \"expiryNode\": \"expiry\", \"outOfQuotaNode\": \"out-of-quota\", \"webhookErrorNode\": \"out-of-quota\", \"alreadyExistsNode\": \"exists\", \"selectedRedemptionStore\": \"default\", \"selectedRedemptionPeriodID\": \"0\"}'),
(2, '2021-07-21 00:00:00', '2021-07-21 00:00:00', NULL, 20, 110, 'coupon-message', 100, '{\"media\": \"https://www.kinnso.com/offers/common/20_chatbot_coupon_survey/journey_kv.jpg\", \"message\": \"/此乃系統自動回訊，請不要回覆此訊息/\\n\\n你好！\\n🎊恭喜你已成功登記為「50+友」會員！\\n我們將會定期推出不同的會員限定優惠及活動，等你享用！🥳\\n\\n「50+友」現送你$20消費折扣🎁，立即到「50+友網店」https://50addoil.com/ 揀你的心水產品，並於結帳時套用優惠碼，即可全單減$20 (購物滿$100可使用)！😍 \\n\\n🔔提提你，此優惠碼只限使用一次，不可轉贈他人或重覆使用\\n\\n以下為優惠碼👇🏻\", \"nextNode\": \"coupon-code\", \"schedule\": null}'),
(3, '2021-07-21 00:00:00', '2021-07-21 00:00:00', NULL, 20, 120, 'coupon-code', 100, '{\"media\": null, \"message\": \"{{uniqueCode}}\", \"nextNode\": \"member-activity\", \"schedule\": null}'),
(4, '2021-07-21 00:00:00', '2021-07-21 00:00:00', NULL, 20, 130, 'out-of-quota', 100, '{\"media\": null, \"message\": \"真係唔好意思！優惠太受歡迎，已經派曬喇！😓\", \"nextNode\": null, \"schedule\": null}'),
(5, '2021-07-21 00:00:00', '2021-07-21 00:00:00', NULL, 20, 140, 'expiry', 100, '{\"media\": null, \"message\": \"多謝支持「50+友」！\\n呢個活動已經完滿結束。\\n\\n想知道更多「50+友」商品優惠和最新推廣消息，可以click入呢度：\\nhttps://50addoil.com/\", \"nextNode\": null, \"schedule\": null}'),
(6, '2021-07-21 00:00:00', '2021-07-21 00:00:00', NULL, 20, 150, 'exists', 100, '{\"media\": null, \"message\": \"好多謝你對「50+友」嘅商品感興趣！\\n請注意，每人只可參加換領活動一次。根據我哋嘅紀錄，你早前已經成功獲取「50+博覽」電子入場券。\\n\\n想知道更多「50+友」商品優惠和最新推廣消息，可以click入呢度：\\nhttps://50addoil.com/\", \"nextNode\": null, \"schedule\": null}'),
(7, '2021-07-21 00:00:00', '2021-07-21 00:00:00', NULL, 20, 160, 'member-activity', 200, '{\"media\": null, \"message\": \"有好消息！🤩「50+友」將會舉辦一系列「會員尊享活動」！🥳\\n請問你對以下哪一個活動最感興趣呢（可選多於一項，如輸入：1,3,4）？\\n\\n手機修圖工作坊              請輸入     1\\n參觀有機靈芝培植場       請輸入     2\\n椅子瑜伽工作坊              請輸入     3\\nWhatsApp貼圖工作坊    請輸入     4\\n日本米及清酒工作坊       請輸入     5\", \"options\": {\"any\": \"member-email\"}, \"schedule\": null}'),
(8, '2021-07-21 00:00:00', '2021-07-21 00:00:00', NULL, 20, 170, 'member-email', 200, '{\"media\": null, \"message\": \"請輸入你的電郵地址👇🏻 等我地可以第一時間通知你報名詳情同最新優惠！🥳\", \"options\": {\"any\": \"received-generic-reply\"}, \"schedule\": null}'),
(9, '2021-07-21 00:00:00', '2021-07-21 00:00:00', NULL, 20, 180, 'received-generic-reply', 100, '{\"media\": null, \"message\": \"收到了，感謝支持「50+友」！我們會繼續搜羅更多優惠和服務給你💃\\n\\n記得讚好我們的Facebook專頁，會有更多適合50+的生活資訊😍 \\nhttps://www.facebook.com/50addoil\\n\\n仲有！記得去「50+友」網店享用你的$20消費折扣啦！🥳*\\nhttps://50addoil.com/\", \"nextNode\": null, \"schedule\": null}');