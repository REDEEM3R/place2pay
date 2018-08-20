<?php


$element = new StdClass();
$element->oPurchaseRequest = new StdClass();
$element->oPurchaseRequest->MembershipNumber = $MembershipNumber;
$element->oPurchaseRequest->SessionID = $SessionID;
$element->oPurchaseRequest->Password = $Password;
$element->oPurchaseRequest->Gender = $Gender;
$element->oPurchaseRequest->RatePlanID = $RatePlanID;
$element->oPurchaseRequest->CCNumber = $CCNumber;
$element->oPurchaseRequest->CCExpireMonth = $CCExpireMonth;
$element->oPurchaseRequest->CCExpireYear = $CCExpireYear;
$element->oPurchaseRequest->CCCode = $CCCode;
$element->oPurchaseRequest->Zip = $Zip;

// We can take in our arguments from the console if we're using PHP CLI
$MembershipNumber = $argv[1];
$SessionID = $argv[2];
$Password = $argv[3];
$Gender = $argv[4];
$RatePlanID = $argv[5];
$CCNumber = $argv[6];
$CCExpireMonth = $argv[7];
$CCExpireYear = $argv[8];
$CCCode = $argv[9];
$Zip = $argv[10];
 
// Create the object we'll pass back over the SOAP interface. This is the MAGIC!
$element = new StdClass();
$element->oPurchaseRequest = new StdClass();
$element->oPurchaseRequest->MembershipNumber = $MembershipNumber;
$element->oPurchaseRequest->SessionID = $SessionID;
$element->oPurchaseRequest->Password = $Password;
$element->oPurchaseRequest->Gender = $Gender;
$element->oPurchaseRequest->RatePlanID = $RatePlanID;
$element->oPurchaseRequest->CCNumber = $CCNumber;
$element->oPurchaseRequest->CCExpireMonth = $CCExpireMonth;
$element->oPurchaseRequest->CCExpireYear = $CCExpireYear;
$element->oPurchaseRequest->CCCode = $CCCode;
$element->oPurchaseRequest->Zip = $Zip;
 
// setup some SOAP options
echo "Setting up SOAP options\n";
$soap_options = array(
        'trace'       => 1,     // traces let us look at the actual SOAP messages later
        'exceptions'  => 1 );
 
 
// configure our WSDL location
echo "Configuring WSDL\n";
$wsdl = "https://locationofservices.tld/asterisk.asmx?WSDL";
 
 
// Make sure the PHP-Soap module is installed
echo "Checking SoapClient exists\n";
if (!class_exists('SoapClient'))
{
        die ("You haven't installed the PHP-Soap module.");
}
 
// we use the WSDL file to create a connection to the web service
echo "Creating webservice connection to $wsdl\n";
$webservice = new SoapClient($wsdl,$soap_options);
 
echo "Attempting Purchase\n";
try {
        $result = $webservice->Purchase($element);
 
        // save our results to some variables
        $TransactionID = $result->PurchaseResult->TransactionID;
        $ResponseCode = $result->PurchaseResult->ResponseCode;
        $ResponseDetail = $result->PurchaseResult->ResponseDetail;
        $AddMinutes = $result->PurchaseResult->AddMinutes;
 
        // perform some logic, output the data to Asterisk, or whatever you want to do with it.
 
} catch (SOAPFault $f) {
        // handle the fault here
}
 
echo "Script complete\n\n";

?>