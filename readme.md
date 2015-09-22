###Wechat Notification

1、git clone git@github.com:ety001/notifications.git

2、composer install  #安装wechat扩展等

3、参照.env_example新建一个.env文件，配置以WX_开头的选项

4、去微信测试公众号后台建立消息模板如下

```
{{first.DATA}}
```

5、发消息到http://xxx/wechat/send?msg=your_message即可收到微信提醒。
