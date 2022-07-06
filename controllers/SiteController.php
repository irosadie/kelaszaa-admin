<?php

namespace app\controllers;

use app\models\helpers\LoginForm;
use app\models\helpers\PasswordResetRequestForm;
use app\models\helpers\ResendVerificationEmailForm;
use app\models\helpers\ResetPasswordForm;
use app\models\helpers\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;
use \Firebase\JWT\JWT;

class SiteController extends Controller
{
    public $title = "...";

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = '@app/views/layouts/frontend';
        $this->redirectByRole();
    }

    public function actionJwt()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

        $privateKey = <<<EOD
        -----BEGIN RSA PRIVATE KEY-----
        MIIEoQIBAAKCAQBulo5okQBpQ4cPvU8Fz9OnAFdOQj98a4n6n3tWzOq4voty3CFr
        BtGShai5/H8F3QykGMfz/H5qcboWVhWS3wF5AKM4SaSdBzvvrxljwxafR6c27s0b
        ZlImS2rCM+iJ2pMU5rRLbosvbh5BL8d0913si8Net5Wh05LwjuSg8lXCrrv62oSG
        lXK/vHsGzGxVLWtXDTuKbXtg++EVeKNTU3BdfN12mYKpWnyjyfzgngCfmXqT6Tpc
        DsDFedCVTsE6M7wsIeA7VWp7aYf1c4luSwApcdl2sZqZfvQ39Nxlrkzr276+6CAo
        BRrGI6r0QtIJT+VFMrxm1StiMgKooUnUXt1JAgMBAAECggEAWOC5fUKkQPVblAPC
        gdpAsWQtxqCpo1ZOY1VPbDhuoKHLMznFxd2KCydOroNGlzDL2wprkSSpeOUD+LWg
        yxRde6FOjItrOCS7P+vLDQmaodKViimsQEwg09Qi7geH6Vr4gIDWABXM2Qa4XA2J
        UPEJ1HWTRAlpwdBntNwy1UNON5Lmo0fETOf3LUahQZBRaQUkA4dw0CcIJTEmSQyI
        z38ehbaELHxrALXmTgq3fzG3DA3PkL38PILRKaSa0Cu8gRk5MVGjahfbTst2rvO7
        xD7KpUKqCdlGeODt9mhSvz2CQMLzk1JxNmXZlckhwUMwlKBnOCpruKlD2jirBoMz
        2QZ08QKBgQCvXoLl/OVCVwGzkb9ecry7hpr7ohF6F2shtP/yzSS/600E1pJM/zJb
        bBHxZjMPBoHcNSiD8Oby3ws8yaW+P6NAYCj6PmL8ORrFUpS1b+rmhyQQuvh9H2TG
        4X0CfOOw9GwRTqfJfSnUZnt9Z+CCsGiUxyi3s2T00Cy/WLflyRnMPQKBgQChbx1Z
        uLr5TRlyVjlLGV/IQ3HumwRqQCeCPMcgcEqqVl7xkYbelLuWj8TjXbdfKU8+EHRe
        +nGIXZbzOtQa6/SZyFUp9ObbMimjsDNK9LcTqQ2qMDR4MeAXeqW1PDVjkgsUu//8
        GugvINpOF2e4ZSbZi/62thFe10myEvJ0IDdp/QKBgApeeTjmChwxDs2ySXFpMVWZ
        MOK+A3CHn/L/Q770eD7Mx+IIWeApmq2jJzBxzYUO6nC9x+Z9QPqNS5nNhnpIQvvU
        c7fQJoQpUDbomhhcRLRauzwuAfFMAOZtgLm77q6Q1S70yqD0QJvpb1XL5Y90pMpf
        OD4cYlTDVlH+CemRSXA1AoGAD+wluPO/fVddaymV/RJDoD0Gv8IKbXBmdTIJcemh
        c6uRMcJoywtgdTk48jQMIhaw+i4lTDjnecvBV7w1r3IWbhSUUc8V9gi8kqiOZpY8
        eHP0JRUcF77Tm309WuLVYTzGWwUv+m2s+PkEjy8/y7AuZMaqGNtcWz5gxm+X+lwz
        GwECgYAjnCEAgg5HRLMZxNrLL0W8re9MTjmXG4bexMsFWHLHCLUUEDoyxTyY1nKM
        G1UGsv9jmtxhzePay9+AdV/sIje+79bd1d7HRawLrpeZ/4HJhGYlMt7qk18ujypw
        zidqYj7VGvB+3d8m8qVyKvBM316PY9o3bjphFIqoYHgr9xonAQ==
        -----END RSA PRIVATE KEY-----
        EOD;

        $payload = [
            "sub" => "88hi5czqr438a9kuu2no7o552qdmwed157y67zjakqlu0ete", // unique user id string
            "name" => "Imron Rosadi", // full name of user
            "exp" => time() + 60 * 1000 // 10 minute expiration
        ];

        try {
            $token = JWT::encode($payload, $privateKey, 'RS256');
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(array("token" => $token));
        } catch (\Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo $e->getMessage();
        }
    }


    public function actionHash($str)
    {
        return Yii::$app->security->generatePasswordHash($str);
    }

    protected function redirectByRole()
    {
        return $this->redirect(Yii::$app->homeUrl . 'auth/login');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $this->layout = 'login';
        if (!Yii::$app->user->isGuest) {
            // return $this->goHome();
            $this->redirectByRole();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // return $this->goBack();
            $this->redirectByRole();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $this->layout = 'password';

        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        $this->layout = 'password';

        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @return yii\web\Response
     * @throws BadRequestHttpException
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
                return $this->goHome();
            }
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }

    public function actionNotAllowed()
    {
        $this->layout = 'noauth';
        return $this->render('403');
    }

    public function actionError()
    {
        $this->layout = 'noauth';
        return $this->render('error');
    }
}