Yii 2 Messente SMS sender
==============

Yii component for sending text messages to messente. 
Before sending to messente, it also checks if phone number is valid for specified country and if phone type is mobile.


How to use?
==============
##Installation with Composer
Just add the line under `require` object in your `composer.json` file.
``` json
{
  "require": {
    "nuffic/yii2-messente" : "dev-develop"
  }
}
```
then run 

``` console
$> composer update
```

##Configuration
Now add following in to your `components` section of config. 

``` php
    'sms' => [
                'class'    => 'nuffic\messente\SMS',
                'username' => '<your API username>',
                'password' => '<your API password>',
                'from'     => '<default sender>' #optional
            ],
```

##Usage 

``` php
    Yii::$app->sms->send('EE', 'Content here', '+372 111 1111', 'SenderHere');
```