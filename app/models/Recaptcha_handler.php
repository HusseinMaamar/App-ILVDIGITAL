<?php 
namespace models;
// recaptcha_handler.php
require 'vendor/autoload.php';
use Google\Cloud\RecaptchaEnterprise\V1\RecaptchaEnterpriseServiceClient;
use Google\Cloud\RecaptchaEnterprise\V1\Event;
use Google\Cloud\RecaptchaEnterprise\V1\Assessment;
use Google\Cloud\RecaptchaEnterprise\V1\TokenProperties\InvalidReason;

class Recaptcha_handler
{
    public function create_assessment(
        string $recaptchaKey,
        string $token,
        string $project,
        string $action
    ): void {
        $client = new RecaptchaEnterpriseServiceClient();
        $projectName = $client->projectName($project);
    
        $event = (new Event())
            ->setSiteKey($recaptchaKey)
            ->setToken($token);
    
        $assessment = (new Assessment())
            ->setEvent($event);
    
        try {
            $response = $client->createAssessment(
                $projectName,
                $assessment
            );
    
            if ($response->getTokenProperties()->getValid() == false) {
                printf('The CreateAssessment() call failed because the token was invalid for the following reason: ');
                printf(InvalidReason::name($response->getTokenProperties()->getInvalidReason()));
                return;
            }
    
            if ($response->getTokenProperties()->getAction() == $action) {
                printf('The score for the protection action is:');
                printf($response->getRiskAnalysis()->getScore());
            } else {
                printf('The action attribute in your reCAPTCHA tag does not match the action you are expecting to score');
            }
        } catch (Exception $e) {
            printf('CreateAssessment() call failed with the following error: ');
            printf($e);
        }
    }
}