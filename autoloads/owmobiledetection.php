<?php

class OWMobileDetection
{
	/*!
	 Constructor
	 */
	function __construct()
	{
		$this->Operators = array( 'owmobiledetection','owmobileredirectionstarter');
	}
	
	/*!
	 Returns the operators in this class.
	 */
	function &operatorList()
	{
		return $this->Operators;
	}

	/*!
	 \return true to tell the template engine that the parameter list
	 exists per operator type, this is needed for operator classes
	 that have multiple operators.
	 */
	function namedParameterPerOperator()
	{
		return true;
	}
	
	/*!
	 \Executes the needed operator(s).
	 \Checks operator names, and calls the appropriate functions.
	 */
	function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
	{
		switch ( $operatorName )
		{
			case 'owmobiledetection':
				{
					$mobileUtils = new OWMobileUtils();
					$operatorValue = $mobileUtils->OWMobileDetection();
				}
				break;
		    case 'owmobileredirectionstarter':
                {
                	$mobileUtils = new OWMobileUtils();
                    $operatorValue = $mobileUtils->OWMobileRedirectionStarter($operatorValue);
                }
                break;  
		}
	}

	/// \privatesection
	var $Operators;
}

?>