<?php


namespace nuffic\messente;

use GuzzleHttp\Client;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberType;
use libphonenumber\PhoneNumberUtil;
use yii\base\Component;
use yii\base\Exception;
use yii\base\InvalidParamException;

class SMS extends Component
{

    /** @var  string API username */
    public $username;

    /** @var  string API password */
    public $password;

    /** @var  string messente API URL */
    public $apiUrl = 'http://api2.messente.com/send_sms/';

    /** @var  string Default from number */
    public $from;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (empty($this->username) || empty($this->password)) {
            throw new InvalidParamException('username or password');
        }
    }

    /**
     * @param string      $country country code in ISO 3166-1 two letter format @link https://www.iso.org/obp/ui/#search
     * @param string      $message SMS content
     * @param string      $to      number to send SMS to
     * @param null|string $from    number that SMS was sent from
     *
     * @return string
     * @throws InvalidParamException
     */
    public function send($country, $message, $to, $from = null)
    {

        if (empty($from)) {
            if (empty($this->from)) {
                throw new InvalidParamException('from');
            } else {
                $from = $this->from;
            }
        }

        $validNumber = $this->validateNumber($to, $country);
        if (!$validNumber) {
            return \Yii::t('app', 'Invalid number: ' . $to . ' for country code ' . $country);
        }

        $client = new Client();
        $response = $client->post(
            $this->apiUrl,
            [
                'form_params' => [
                    'username' => $this->username,
                    'password' => $this->password,
                    'text'     => $message,
                    'from'     => $from,
                    'to'       => $validNumber
                ]
            ]
        );

        return $response->getBody()->getContents();
    }

    /**
     * @param string $number  phone number to validate
     * @param string $country country code in ISO 3166-1 two letter format @link https://www.iso.org/obp/ui/#search
     *
     * @return bool|string  false or phone number in international format
     */
    public function validateNumber($number, $country)
    {
        $validator = PhoneNumberUtil::getInstance();

        try {
            $numberProto = $validator->parse($number, $country);
            if ($validator->isValidNumber($numberProto) && $validator->getNumberType($numberProto) == PhoneNumberType::MOBILE) {
                return $validator->format($numberProto, PhoneNumberFormat::INTERNATIONAL);
            } else {
                return false;
            }
        } catch (NumberParseException $e) {
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
}
