<?php

  $session = Session::getInstance();
  $insurancePolicy = $session->getAttribute('insurancePolicy');
  $session->removeAttribute('insurancePolicy');

  if (!$insurancePolicy || !$request->getAlphaNumericParameter('accountId') ) {
    $url = Util::buildURL($here, ADMIN_VERIFY_INSURANCE_CMD,  [ 'accountId' => $request->getAlphaNumericParameter('accountId') ]);
    Util::jump($url);
    exit;

  } else {
    $ebiWillHonor = $insurancePolicy->getEBIbenefits();
    $salesforce = new SalesforceAccountManager();
    $updated_account = $salesforce->updateInsuranceInfo($request->getAlphaNumericParameter('accountId'), $insurancePolicy);

    include('templates/admin/admin.header.tem.php');

    if ($updated_account) {
      $view['pageTitle'] = "Salesforce Account Updated";
      echo "<div class='warningMessage'>Your Account has been updated.</div>";

    } else {
      $view['pageTitle'] = "API Error Encountered";
      echo "<div class='errorMessage'>There was a problem updating the salesforce account. A notification was sent containing the error message.</div>";
    }

    include('templates/admin/admin.footer.tem.php');
    exit;
    
  }

?>
