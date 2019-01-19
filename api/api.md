##通信协议简单分析

###获取商品列表

`https://api.nike.com/launch/launch_views/v2/?filter=productId(dc298414-a87e-5c9d-9ba5-bd0e980b1f03)` //多个商品用逗号隔开

###抽签,总共要请求两个接口,如下

+ `https://api.nike.com/buy/checkout_previews/v2/9586260c-96bd-40d9-8800-f9aab5757e35` 后面的ID不详

+ Request Payload
<pre>
{
	"request": {
		"email": "邮箱",
		"country": "CN",
		"currency": "CNY",
		"locale": "zh_CN",
		"channel": "SNKRS",
		"clientInfo": {
			"deviceId": "生成规则不详,是一个Lenght为1468的字符串"
		},
		"items": [{
			"id": "5912f1e1-3a8d-5eb1-bffd-6d57cd09f4a6",
			"skuId": "ee3f70a3-6841-587b-b723-738c651f78c6",
			"quantity": 1,
			"recipient": {
				"firstName": "名",
				"lastName": "姓"
			},
			"shippingAddress": {
				"address1": "具体地址,例如,3号楼1单元101",
				"address2": "小区名字",
				"city": "北京市",
				"state": "CN-11",
				"postalCode": "邮编",
				"county": "地区,例如,朝阳区",
				"country": "CN"
			},
			"contactInfo": {
				"email": "邮箱",
				"phoneNumber": "电话"
			},
			"shippingMethod": "GROUND_SERVICE"
		}]
	}
}
</pre>

+ Request Headers

<pre>
:authority: api.nike.com
:method: PUT
:path: /buy/checkout_previews/v2/9586260c-96bd-40d9-8800-f9aab5757e35
:scheme: https
accept: application/json
accept-encoding: gzip, deflate, br
accept-language: zh-CN,zh;q=0.9
appid: com.nike.commerce.snkrs.web
authorization: Bearer Access Token
cache-control: no-cache
content-length: 2054
content-type: application/json; charset=UTF-8
origin: https://www.nike.com
pragma: no-cache
referer: https://www.nike.com/cn/launch/?productId=5912f1e1-3a8d-5eb1-bffd-6d57cd09f4a6&s=upcoming&size=8
user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36
x-b3-parentspanid: 0f6edcbdebfaf689
x-b3-spanid: 0b70f361bd9ecd83
x-b3-traceid: ae955ffbb857c02b
</pre>

+ `https://api.nike.com/payment/preview/v2`

+ Request Headers
<pre>
:authority: api.nike.com
:method: POST
:path: /payment/preview/v2
:scheme: https
accept: */*
accept-encoding: gzip, deflate, br
accept-language: zh-CN,zh;q=0.9
appid: com.nike.commerce.snkrs.web
authorization: Bearer AccessToken
cache-control: no-cache
content-length: 698
content-type: application/json; charset=UTF-8
origin: https://www.nike.com
pragma: no-cache
referer: https://www.nike.com/cn/launch/?productId=5912f1e1-3a8d-5eb1-bffd-6d57cd09f4a6&s=upcoming&size=8
user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36
x-b3-parentspanid: 0f6edcbdebfaf689
x-b3-spanid: 0b70f361bd9ecd83
x-b3-traceid: ae955ffbb857c02b
</pre>

+ Request Payloer

<pre>
{
	"checkoutId": "9586260c-96bd-40d9-8800-f9aab5757e35", //这个ID不太确定是哪里生成的
	"total": 2099,
	"currency": "CNY",
	"country": "CN",
	"items": [{
		"productId": "5912f1e1-3a8d-5eb1-bffd-6d57cd09f4a6",    //商品ID
		"shippingAddress": {
			"address1": "同上",
			"address2": "同上",
			"city": "同上",
			"country": "CN",
			"county": "同上",
			"postalCode": "同上",
			"state": "CN-11"
		}
	}],
	"paymentInfo": [{
		"id": "698d1eff-9eae-4c5e-a9cc-f1b9cf3f4f2a",   //这个ID暂时也不太确定是哪里生成的
		"billingInfo": {
			"name": {
				"firstName": "同上",
				"lastName": "同上"
			},
			"address": {
				"address1": "同上",
				"address2": "同上",
				"city": "同上",
				"country": "CN",
				"county": "同上",
				"postalCode": "同上",
				"state": "CN-11"
			},
			"contactInfo": {
				"phoneNumber": "手机号码",
				"email": "登录邮箱"
			}
		},
		"type": "Alipay"
	}]
}
</pre>