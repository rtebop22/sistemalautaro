<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "paquetes_provisioninfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$paquetes_provision_update = NULL; // Initialize page object first

class cpaquetes_provision_update extends cpaquetes_provision {

	// Page ID
	var $PageID = 'update';

	// Project ID
	var $ProjectID = "{9FD9BA28-0339-4B41-9A45-0CAE935EFE3A}";

	// Table name
	var $TableName = 'paquetes_provision';

	// Page object name
	var $PageObjName = 'paquetes_provision_update';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}
	var $AuditTrailOnAdd = FALSE;
	var $AuditTrailOnEdit = TRUE;
	var $AuditTrailOnDelete = FALSE;
	var $AuditTrailOnView = FALSE;
	var $AuditTrailOnViewData = FALSE;
	var $AuditTrailOnSearch = FALSE;

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = TRUE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (paquetes_provision)
		if (!isset($GLOBALS["paquetes_provision"]) || get_class($GLOBALS["paquetes_provision"]) == "cpaquetes_provision") {
			$GLOBALS["paquetes_provision"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["paquetes_provision"];
		}

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'update', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'paquetes_provision', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (usuarios)
		if (!isset($UserTable)) {
			$UserTable = new cusuarios();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("paquetes_provisionlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->NroPedido->SetVisibility();
		$this->NroPedido->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->Serie_Netbook->SetVisibility();
		$this->SN->SetVisibility();
		$this->Marca_Arranque->SetVisibility();
		$this->Serie_Server->SetVisibility();
		$this->Id_Motivo->SetVisibility();
		$this->Id_Tipo_Extraccion->SetVisibility();
		$this->Id_Estado_Paquete->SetVisibility();
		$this->Id_Tipo_Paquete->SetVisibility();
		$this->Apellido_Nombre_Solicitante->SetVisibility();
		$this->Dni->SetVisibility();
		$this->Email_Solicitante->SetVisibility();
		$this->Usuario->SetVisibility();
		$this->Fecha_Actualizacion->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $paquetes_provision;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($paquetes_provision);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();

			// Handle modal response
			if ($this->IsModal) {
				$row = array();
				$row["url"] = $url;
				echo ew_ArrayToJson(array($row));
			} else {
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewUpdateForm";
	var $IsModal = FALSE;
	var $RecKeys;
	var $Disabled;
	var $Recordset;
	var $UpdateCount = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Try to load keys from list form
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		if (@$_POST["a_update"] <> "") {

			// Get action
			$this->CurrentAction = $_POST["a_update"];
			$this->LoadFormValues(); // Get form values

			// Validate form
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->setFailureMessage($gsFormError);
			}
		} else {
			$this->LoadMultiUpdateValues(); // Load initial values to form
		}
		if (count($this->RecKeys) <= 0)
			$this->Page_Terminate("paquetes_provisionlist.php"); // No records selected, return to list
		switch ($this->CurrentAction) {
			case "U": // Update
				if ($this->UpdateRows()) { // Update Records based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up update success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				} else {
					$this->RestoreFormValues(); // Restore form values
				}
		}

		// Render row
		$this->RowType = EW_ROWTYPE_EDIT; // Render edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Load initial values to form if field values are identical in all selected records
	function LoadMultiUpdateValues() {
		$this->CurrentFilter = $this->GetKeyFilter();

		// Load recordset
		if ($this->Recordset = $this->LoadRecordset()) {
			$i = 1;
			while (!$this->Recordset->EOF) {
				if ($i == 1) {
					$this->NroPedido->setDbValue($this->Recordset->fields('NroPedido'));
					$this->Serie_Netbook->setDbValue($this->Recordset->fields('Serie_Netbook'));
					$this->SN->setDbValue($this->Recordset->fields('SN'));
					$this->Marca_Arranque->setDbValue($this->Recordset->fields('Marca_Arranque'));
					$this->Serie_Server->setDbValue($this->Recordset->fields('Serie_Server'));
					$this->Id_Motivo->setDbValue($this->Recordset->fields('Id_Motivo'));
					$this->Id_Tipo_Extraccion->setDbValue($this->Recordset->fields('Id_Tipo_Extraccion'));
					$this->Id_Estado_Paquete->setDbValue($this->Recordset->fields('Id_Estado_Paquete'));
					$this->Id_Tipo_Paquete->setDbValue($this->Recordset->fields('Id_Tipo_Paquete'));
					$this->Apellido_Nombre_Solicitante->setDbValue($this->Recordset->fields('Apellido_Nombre_Solicitante'));
					$this->Dni->setDbValue($this->Recordset->fields('Dni'));
					$this->Email_Solicitante->setDbValue($this->Recordset->fields('Email_Solicitante'));
					$this->Usuario->setDbValue($this->Recordset->fields('Usuario'));
					$this->Fecha_Actualizacion->setDbValue($this->Recordset->fields('Fecha_Actualizacion'));
				} else {
					if (!ew_CompareValue($this->NroPedido->DbValue, $this->Recordset->fields('NroPedido')))
						$this->NroPedido->CurrentValue = NULL;
					if (!ew_CompareValue($this->Serie_Netbook->DbValue, $this->Recordset->fields('Serie_Netbook')))
						$this->Serie_Netbook->CurrentValue = NULL;
					if (!ew_CompareValue($this->SN->DbValue, $this->Recordset->fields('SN')))
						$this->SN->CurrentValue = NULL;
					if (!ew_CompareValue($this->Marca_Arranque->DbValue, $this->Recordset->fields('Marca_Arranque')))
						$this->Marca_Arranque->CurrentValue = NULL;
					if (!ew_CompareValue($this->Serie_Server->DbValue, $this->Recordset->fields('Serie_Server')))
						$this->Serie_Server->CurrentValue = NULL;
					if (!ew_CompareValue($this->Id_Motivo->DbValue, $this->Recordset->fields('Id_Motivo')))
						$this->Id_Motivo->CurrentValue = NULL;
					if (!ew_CompareValue($this->Id_Tipo_Extraccion->DbValue, $this->Recordset->fields('Id_Tipo_Extraccion')))
						$this->Id_Tipo_Extraccion->CurrentValue = NULL;
					if (!ew_CompareValue($this->Id_Estado_Paquete->DbValue, $this->Recordset->fields('Id_Estado_Paquete')))
						$this->Id_Estado_Paquete->CurrentValue = NULL;
					if (!ew_CompareValue($this->Id_Tipo_Paquete->DbValue, $this->Recordset->fields('Id_Tipo_Paquete')))
						$this->Id_Tipo_Paquete->CurrentValue = NULL;
					if (!ew_CompareValue($this->Apellido_Nombre_Solicitante->DbValue, $this->Recordset->fields('Apellido_Nombre_Solicitante')))
						$this->Apellido_Nombre_Solicitante->CurrentValue = NULL;
					if (!ew_CompareValue($this->Dni->DbValue, $this->Recordset->fields('Dni')))
						$this->Dni->CurrentValue = NULL;
					if (!ew_CompareValue($this->Email_Solicitante->DbValue, $this->Recordset->fields('Email_Solicitante')))
						$this->Email_Solicitante->CurrentValue = NULL;
					if (!ew_CompareValue($this->Usuario->DbValue, $this->Recordset->fields('Usuario')))
						$this->Usuario->CurrentValue = NULL;
					if (!ew_CompareValue($this->Fecha_Actualizacion->DbValue, $this->Recordset->fields('Fecha_Actualizacion')))
						$this->Fecha_Actualizacion->CurrentValue = NULL;
				}
				$i++;
				$this->Recordset->MoveNext();
			}
			$this->Recordset->Close();
		}
	}

	// Set up key value
	function SetupKeyValues($key) {
		$sKeyFld = $key;
		if (!is_numeric($sKeyFld))
			return FALSE;
		$this->NroPedido->CurrentValue = $sKeyFld;
		return TRUE;
	}

	// Update all selected rows
	function UpdateRows() {
		global $Language;
		$conn = &$this->Connection();
		$conn->BeginTrans();
		if ($this->AuditTrailOnEdit) $this->WriteAuditTrailDummy($Language->Phrase("BatchUpdateBegin")); // Batch update begin

		// Get old recordset
		$this->CurrentFilter = $this->GetKeyFilter();
		$sSql = $this->SQL();
		$rsold = $conn->Execute($sSql);

		// Update all rows
		$sKey = "";
		foreach ($this->RecKeys as $key) {
			if ($this->SetupKeyValues($key)) {
				$sThisKey = $key;
				$this->SendEmail = FALSE; // Do not send email on update success
				$this->UpdateCount += 1; // Update record count for records being updated
				$UpdateRows = $this->EditRow(); // Update this row
			} else {
				$UpdateRows = FALSE;
			}
			if (!$UpdateRows)
				break; // Update failed
			if ($sKey <> "") $sKey .= ", ";
			$sKey .= $sThisKey;
		}

		// Check if all rows updated
		if ($UpdateRows) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			$rsnew = $conn->Execute($sSql);
			if ($this->AuditTrailOnEdit) $this->WriteAuditTrailDummy($Language->Phrase("BatchUpdateSuccess")); // Batch update success
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->AuditTrailOnEdit) $this->WriteAuditTrailDummy($Language->Phrase("BatchUpdateRollback")); // Batch update rollback
		}
		return $UpdateRows;
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->NroPedido->FldIsDetailKey)
			$this->NroPedido->setFormValue($objForm->GetValue("x_NroPedido"));
		if (!$this->Serie_Netbook->FldIsDetailKey) {
			$this->Serie_Netbook->setFormValue($objForm->GetValue("x_Serie_Netbook"));
		}
		$this->Serie_Netbook->MultiUpdate = $objForm->GetValue("u_Serie_Netbook");
		if (!$this->SN->FldIsDetailKey) {
			$this->SN->setFormValue($objForm->GetValue("x_SN"));
		}
		$this->SN->MultiUpdate = $objForm->GetValue("u_SN");
		if (!$this->Marca_Arranque->FldIsDetailKey) {
			$this->Marca_Arranque->setFormValue($objForm->GetValue("x_Marca_Arranque"));
		}
		$this->Marca_Arranque->MultiUpdate = $objForm->GetValue("u_Marca_Arranque");
		if (!$this->Serie_Server->FldIsDetailKey) {
			$this->Serie_Server->setFormValue($objForm->GetValue("x_Serie_Server"));
		}
		$this->Serie_Server->MultiUpdate = $objForm->GetValue("u_Serie_Server");
		if (!$this->Id_Motivo->FldIsDetailKey) {
			$this->Id_Motivo->setFormValue($objForm->GetValue("x_Id_Motivo"));
		}
		$this->Id_Motivo->MultiUpdate = $objForm->GetValue("u_Id_Motivo");
		if (!$this->Id_Tipo_Extraccion->FldIsDetailKey) {
			$this->Id_Tipo_Extraccion->setFormValue($objForm->GetValue("x_Id_Tipo_Extraccion"));
		}
		$this->Id_Tipo_Extraccion->MultiUpdate = $objForm->GetValue("u_Id_Tipo_Extraccion");
		if (!$this->Id_Estado_Paquete->FldIsDetailKey) {
			$this->Id_Estado_Paquete->setFormValue($objForm->GetValue("x_Id_Estado_Paquete"));
		}
		$this->Id_Estado_Paquete->MultiUpdate = $objForm->GetValue("u_Id_Estado_Paquete");
		if (!$this->Id_Tipo_Paquete->FldIsDetailKey) {
			$this->Id_Tipo_Paquete->setFormValue($objForm->GetValue("x_Id_Tipo_Paquete"));
		}
		$this->Id_Tipo_Paquete->MultiUpdate = $objForm->GetValue("u_Id_Tipo_Paquete");
		if (!$this->Apellido_Nombre_Solicitante->FldIsDetailKey) {
			$this->Apellido_Nombre_Solicitante->setFormValue($objForm->GetValue("x_Apellido_Nombre_Solicitante"));
		}
		$this->Apellido_Nombre_Solicitante->MultiUpdate = $objForm->GetValue("u_Apellido_Nombre_Solicitante");
		if (!$this->Dni->FldIsDetailKey) {
			$this->Dni->setFormValue($objForm->GetValue("x_Dni"));
		}
		$this->Dni->MultiUpdate = $objForm->GetValue("u_Dni");
		if (!$this->Email_Solicitante->FldIsDetailKey) {
			$this->Email_Solicitante->setFormValue($objForm->GetValue("x_Email_Solicitante"));
		}
		$this->Email_Solicitante->MultiUpdate = $objForm->GetValue("u_Email_Solicitante");
		if (!$this->Usuario->FldIsDetailKey) {
			$this->Usuario->setFormValue($objForm->GetValue("x_Usuario"));
		}
		$this->Usuario->MultiUpdate = $objForm->GetValue("u_Usuario");
		if (!$this->Fecha_Actualizacion->FldIsDetailKey) {
			$this->Fecha_Actualizacion->setFormValue($objForm->GetValue("x_Fecha_Actualizacion"));
			$this->Fecha_Actualizacion->CurrentValue = ew_UnFormatDateTime($this->Fecha_Actualizacion->CurrentValue, 7);
		}
		$this->Fecha_Actualizacion->MultiUpdate = $objForm->GetValue("u_Fecha_Actualizacion");
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->NroPedido->CurrentValue = $this->NroPedido->FormValue;
		$this->Serie_Netbook->CurrentValue = $this->Serie_Netbook->FormValue;
		$this->SN->CurrentValue = $this->SN->FormValue;
		$this->Marca_Arranque->CurrentValue = $this->Marca_Arranque->FormValue;
		$this->Serie_Server->CurrentValue = $this->Serie_Server->FormValue;
		$this->Id_Motivo->CurrentValue = $this->Id_Motivo->FormValue;
		$this->Id_Tipo_Extraccion->CurrentValue = $this->Id_Tipo_Extraccion->FormValue;
		$this->Id_Estado_Paquete->CurrentValue = $this->Id_Estado_Paquete->FormValue;
		$this->Id_Tipo_Paquete->CurrentValue = $this->Id_Tipo_Paquete->FormValue;
		$this->Apellido_Nombre_Solicitante->CurrentValue = $this->Apellido_Nombre_Solicitante->FormValue;
		$this->Dni->CurrentValue = $this->Dni->FormValue;
		$this->Email_Solicitante->CurrentValue = $this->Email_Solicitante->FormValue;
		$this->Usuario->CurrentValue = $this->Usuario->FormValue;
		$this->Fecha_Actualizacion->CurrentValue = $this->Fecha_Actualizacion->FormValue;
		$this->Fecha_Actualizacion->CurrentValue = ew_UnFormatDateTime($this->Fecha_Actualizacion->CurrentValue, 7);
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->NroPedido->setDbValue($rs->fields('NroPedido'));
		$this->Serie_Netbook->setDbValue($rs->fields('Serie_Netbook'));
		$this->Id_Hardware->setDbValue($rs->fields('Id_Hardware'));
		$this->SN->setDbValue($rs->fields('SN'));
		$this->Marca_Arranque->setDbValue($rs->fields('Marca_Arranque'));
		$this->Serie_Server->setDbValue($rs->fields('Serie_Server'));
		$this->Id_Motivo->setDbValue($rs->fields('Id_Motivo'));
		$this->Id_Tipo_Extraccion->setDbValue($rs->fields('Id_Tipo_Extraccion'));
		$this->Id_Estado_Paquete->setDbValue($rs->fields('Id_Estado_Paquete'));
		$this->Id_Tipo_Paquete->setDbValue($rs->fields('Id_Tipo_Paquete'));
		$this->Apellido_Nombre_Solicitante->setDbValue($rs->fields('Apellido_Nombre_Solicitante'));
		$this->Dni->setDbValue($rs->fields('Dni'));
		$this->Email_Solicitante->setDbValue($rs->fields('Email_Solicitante'));
		$this->Usuario->setDbValue($rs->fields('Usuario'));
		$this->Fecha_Actualizacion->setDbValue($rs->fields('Fecha_Actualizacion'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->NroPedido->DbValue = $row['NroPedido'];
		$this->Serie_Netbook->DbValue = $row['Serie_Netbook'];
		$this->Id_Hardware->DbValue = $row['Id_Hardware'];
		$this->SN->DbValue = $row['SN'];
		$this->Marca_Arranque->DbValue = $row['Marca_Arranque'];
		$this->Serie_Server->DbValue = $row['Serie_Server'];
		$this->Id_Motivo->DbValue = $row['Id_Motivo'];
		$this->Id_Tipo_Extraccion->DbValue = $row['Id_Tipo_Extraccion'];
		$this->Id_Estado_Paquete->DbValue = $row['Id_Estado_Paquete'];
		$this->Id_Tipo_Paquete->DbValue = $row['Id_Tipo_Paquete'];
		$this->Apellido_Nombre_Solicitante->DbValue = $row['Apellido_Nombre_Solicitante'];
		$this->Dni->DbValue = $row['Dni'];
		$this->Email_Solicitante->DbValue = $row['Email_Solicitante'];
		$this->Usuario->DbValue = $row['Usuario'];
		$this->Fecha_Actualizacion->DbValue = $row['Fecha_Actualizacion'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// NroPedido
		// Serie_Netbook
		// Id_Hardware
		// SN
		// Marca_Arranque
		// Serie_Server
		// Id_Motivo
		// Id_Tipo_Extraccion
		// Id_Estado_Paquete
		// Id_Tipo_Paquete
		// Apellido_Nombre_Solicitante
		// Dni
		// Email_Solicitante
		// Usuario
		// Fecha_Actualizacion

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// NroPedido
		$this->NroPedido->ViewValue = $this->NroPedido->CurrentValue;
		$this->NroPedido->ViewCustomAttributes = "";

		// Serie_Netbook
		$this->Serie_Netbook->ViewValue = $this->Serie_Netbook->CurrentValue;
		if (strval($this->Serie_Netbook->CurrentValue) <> "") {
			$sFilterWrk = "`NroSerie`" . ew_SearchString("=", $this->Serie_Netbook->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `NroSerie`, `NroSerie` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `equipos`";
		$sWhereWrk = "";
		$this->Serie_Netbook->LookupFilters = array("dx1" => "`NroSerie`");
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Serie_Netbook, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Serie_Netbook->ViewValue = $this->Serie_Netbook->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Serie_Netbook->ViewValue = $this->Serie_Netbook->CurrentValue;
			}
		} else {
			$this->Serie_Netbook->ViewValue = NULL;
		}
		$this->Serie_Netbook->ViewCustomAttributes = "";

		// Id_Hardware
		$this->Id_Hardware->ViewValue = $this->Id_Hardware->CurrentValue;
		if (strval($this->Id_Hardware->CurrentValue) <> "") {
			$sFilterWrk = "`NroMac`" . ew_SearchString("=", $this->Id_Hardware->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `NroMac`, `NroMac` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `equipos`";
		$sWhereWrk = "";
		$this->Id_Hardware->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Id_Hardware, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Id_Hardware->ViewValue = $this->Id_Hardware->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Id_Hardware->ViewValue = $this->Id_Hardware->CurrentValue;
			}
		} else {
			$this->Id_Hardware->ViewValue = NULL;
		}
		$this->Id_Hardware->ViewCustomAttributes = "";

		// SN
		$this->SN->ViewValue = $this->SN->CurrentValue;
		$this->SN->ViewCustomAttributes = "";

		// Marca_Arranque
		$this->Marca_Arranque->ViewValue = $this->Marca_Arranque->CurrentValue;
		$this->Marca_Arranque->ViewCustomAttributes = "";

		// Serie_Server
		$this->Serie_Server->ViewValue = $this->Serie_Server->CurrentValue;
		if (strval($this->Serie_Server->CurrentValue) <> "") {
			$sFilterWrk = "`Nro_Serie`" . ew_SearchString("=", $this->Serie_Server->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `Nro_Serie`, `Nro_Serie` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `servidor_escolar`";
		$sWhereWrk = "";
		$this->Serie_Server->LookupFilters = array("dx1" => "`Nro_Serie`");
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Serie_Server, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Serie_Server->ViewValue = $this->Serie_Server->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Serie_Server->ViewValue = $this->Serie_Server->CurrentValue;
			}
		} else {
			$this->Serie_Server->ViewValue = NULL;
		}
		$this->Serie_Server->ViewCustomAttributes = "";

		// Id_Motivo
		if (strval($this->Id_Motivo->CurrentValue) <> "") {
			$sFilterWrk = "`Id_Motivo`" . ew_SearchString("=", $this->Id_Motivo->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `Id_Motivo`, `Detalle` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `motivo_pedido_paquetes`";
		$sWhereWrk = "";
		$this->Id_Motivo->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Id_Motivo, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Id_Motivo->ViewValue = $this->Id_Motivo->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Id_Motivo->ViewValue = $this->Id_Motivo->CurrentValue;
			}
		} else {
			$this->Id_Motivo->ViewValue = NULL;
		}
		$this->Id_Motivo->ViewCustomAttributes = "";

		// Id_Tipo_Extraccion
		if (strval($this->Id_Tipo_Extraccion->CurrentValue) <> "") {
			$sFilterWrk = "`Id_Tipo_Extraccion`" . ew_SearchString("=", $this->Id_Tipo_Extraccion->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `Id_Tipo_Extraccion`, `Detalle` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_extraccion`";
		$sWhereWrk = "";
		$this->Id_Tipo_Extraccion->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Id_Tipo_Extraccion, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Id_Tipo_Extraccion->ViewValue = $this->Id_Tipo_Extraccion->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Id_Tipo_Extraccion->ViewValue = $this->Id_Tipo_Extraccion->CurrentValue;
			}
		} else {
			$this->Id_Tipo_Extraccion->ViewValue = NULL;
		}
		$this->Id_Tipo_Extraccion->ViewCustomAttributes = "";

		// Id_Estado_Paquete
		if (strval($this->Id_Estado_Paquete->CurrentValue) <> "") {
			$sFilterWrk = "`Id_Estado_Paquete`" . ew_SearchString("=", $this->Id_Estado_Paquete->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `Id_Estado_Paquete`, `Detalle` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `estado_paquete`";
		$sWhereWrk = "";
		$this->Id_Estado_Paquete->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Id_Estado_Paquete, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `Detalle` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Id_Estado_Paquete->ViewValue = $this->Id_Estado_Paquete->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Id_Estado_Paquete->ViewValue = $this->Id_Estado_Paquete->CurrentValue;
			}
		} else {
			$this->Id_Estado_Paquete->ViewValue = NULL;
		}
		$this->Id_Estado_Paquete->ViewCustomAttributes = "";

		// Id_Tipo_Paquete
		if (strval($this->Id_Tipo_Paquete->CurrentValue) <> "") {
			$sFilterWrk = "`Id_Tipo_Paquete`" . ew_SearchString("=", $this->Id_Tipo_Paquete->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `Id_Tipo_Paquete`, `Detalle` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_paquete`";
		$sWhereWrk = "";
		$this->Id_Tipo_Paquete->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Id_Tipo_Paquete, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Id_Tipo_Paquete->ViewValue = $this->Id_Tipo_Paquete->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Id_Tipo_Paquete->ViewValue = $this->Id_Tipo_Paquete->CurrentValue;
			}
		} else {
			$this->Id_Tipo_Paquete->ViewValue = NULL;
		}
		$this->Id_Tipo_Paquete->ViewCustomAttributes = "";

		// Apellido_Nombre_Solicitante
		$this->Apellido_Nombre_Solicitante->ViewValue = $this->Apellido_Nombre_Solicitante->CurrentValue;
		if (strval($this->Apellido_Nombre_Solicitante->CurrentValue) <> "") {
			$sFilterWrk = "`Apellido_Nombre`" . ew_SearchString("=", $this->Apellido_Nombre_Solicitante->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `Apellido_Nombre`, `Apellido_Nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `referente_tecnico`";
		$sWhereWrk = "";
		$this->Apellido_Nombre_Solicitante->LookupFilters = array("dx1" => "`Apellido_Nombre`");
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Apellido_Nombre_Solicitante, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Apellido_Nombre_Solicitante->ViewValue = $this->Apellido_Nombre_Solicitante->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Apellido_Nombre_Solicitante->ViewValue = $this->Apellido_Nombre_Solicitante->CurrentValue;
			}
		} else {
			$this->Apellido_Nombre_Solicitante->ViewValue = NULL;
		}
		$this->Apellido_Nombre_Solicitante->ViewCustomAttributes = "";

		// Dni
		$this->Dni->ViewValue = $this->Dni->CurrentValue;
		$this->Dni->ViewCustomAttributes = "";

		// Email_Solicitante
		$this->Email_Solicitante->ViewValue = $this->Email_Solicitante->CurrentValue;
		$this->Email_Solicitante->ViewCustomAttributes = "";

		// Usuario
		$this->Usuario->ViewValue = $this->Usuario->CurrentValue;
		$this->Usuario->ViewCustomAttributes = "";

		// Fecha_Actualizacion
		$this->Fecha_Actualizacion->ViewValue = $this->Fecha_Actualizacion->CurrentValue;
		$this->Fecha_Actualizacion->ViewValue = ew_FormatDateTime($this->Fecha_Actualizacion->ViewValue, 7);
		$this->Fecha_Actualizacion->ViewCustomAttributes = "";

			// NroPedido
			$this->NroPedido->LinkCustomAttributes = "";
			$this->NroPedido->HrefValue = "";
			$this->NroPedido->TooltipValue = "";

			// Serie_Netbook
			$this->Serie_Netbook->LinkCustomAttributes = "";
			$this->Serie_Netbook->HrefValue = "";
			$this->Serie_Netbook->TooltipValue = "";

			// SN
			$this->SN->LinkCustomAttributes = "";
			$this->SN->HrefValue = "";
			$this->SN->TooltipValue = "";

			// Marca_Arranque
			$this->Marca_Arranque->LinkCustomAttributes = "";
			$this->Marca_Arranque->HrefValue = "";
			$this->Marca_Arranque->TooltipValue = "";

			// Serie_Server
			$this->Serie_Server->LinkCustomAttributes = "";
			$this->Serie_Server->HrefValue = "";
			$this->Serie_Server->TooltipValue = "";

			// Id_Motivo
			$this->Id_Motivo->LinkCustomAttributes = "";
			$this->Id_Motivo->HrefValue = "";
			$this->Id_Motivo->TooltipValue = "";

			// Id_Tipo_Extraccion
			$this->Id_Tipo_Extraccion->LinkCustomAttributes = "";
			$this->Id_Tipo_Extraccion->HrefValue = "";
			$this->Id_Tipo_Extraccion->TooltipValue = "";

			// Id_Estado_Paquete
			$this->Id_Estado_Paquete->LinkCustomAttributes = "";
			$this->Id_Estado_Paquete->HrefValue = "";
			$this->Id_Estado_Paquete->TooltipValue = "";

			// Id_Tipo_Paquete
			$this->Id_Tipo_Paquete->LinkCustomAttributes = "";
			$this->Id_Tipo_Paquete->HrefValue = "";
			$this->Id_Tipo_Paquete->TooltipValue = "";

			// Apellido_Nombre_Solicitante
			$this->Apellido_Nombre_Solicitante->LinkCustomAttributes = "";
			$this->Apellido_Nombre_Solicitante->HrefValue = "";
			$this->Apellido_Nombre_Solicitante->TooltipValue = "";

			// Dni
			$this->Dni->LinkCustomAttributes = "";
			$this->Dni->HrefValue = "";
			$this->Dni->TooltipValue = "";

			// Email_Solicitante
			$this->Email_Solicitante->LinkCustomAttributes = "";
			$this->Email_Solicitante->HrefValue = "";
			$this->Email_Solicitante->TooltipValue = "";

			// Usuario
			$this->Usuario->LinkCustomAttributes = "";
			$this->Usuario->HrefValue = "";
			$this->Usuario->TooltipValue = "";

			// Fecha_Actualizacion
			$this->Fecha_Actualizacion->LinkCustomAttributes = "";
			$this->Fecha_Actualizacion->HrefValue = "";
			$this->Fecha_Actualizacion->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// NroPedido
			$this->NroPedido->EditAttrs["class"] = "form-control";
			$this->NroPedido->EditCustomAttributes = "";
			$this->NroPedido->EditValue = $this->NroPedido->CurrentValue;
			$this->NroPedido->ViewCustomAttributes = "";

			// Serie_Netbook
			$this->Serie_Netbook->EditAttrs["class"] = "form-control";
			$this->Serie_Netbook->EditCustomAttributes = "";
			$this->Serie_Netbook->EditValue = ew_HtmlEncode($this->Serie_Netbook->CurrentValue);
			if (strval($this->Serie_Netbook->CurrentValue) <> "") {
				$sFilterWrk = "`NroSerie`" . ew_SearchString("=", $this->Serie_Netbook->CurrentValue, EW_DATATYPE_STRING, "");
			$sSqlWrk = "SELECT `NroSerie`, `NroSerie` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `equipos`";
			$sWhereWrk = "";
			$this->Serie_Netbook->LookupFilters = array("dx1" => "`NroSerie`");
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Serie_Netbook, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->Serie_Netbook->EditValue = $this->Serie_Netbook->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->Serie_Netbook->EditValue = ew_HtmlEncode($this->Serie_Netbook->CurrentValue);
				}
			} else {
				$this->Serie_Netbook->EditValue = NULL;
			}
			$this->Serie_Netbook->PlaceHolder = ew_RemoveHtml($this->Serie_Netbook->FldCaption());

			// SN
			$this->SN->EditAttrs["class"] = "form-control";
			$this->SN->EditCustomAttributes = "";
			$this->SN->EditValue = ew_HtmlEncode($this->SN->CurrentValue);
			$this->SN->PlaceHolder = ew_RemoveHtml($this->SN->FldCaption());

			// Marca_Arranque
			$this->Marca_Arranque->EditAttrs["class"] = "form-control";
			$this->Marca_Arranque->EditCustomAttributes = "";
			$this->Marca_Arranque->EditValue = ew_HtmlEncode($this->Marca_Arranque->CurrentValue);
			$this->Marca_Arranque->PlaceHolder = ew_RemoveHtml($this->Marca_Arranque->FldCaption());

			// Serie_Server
			$this->Serie_Server->EditAttrs["class"] = "form-control";
			$this->Serie_Server->EditCustomAttributes = "";
			$this->Serie_Server->EditValue = ew_HtmlEncode($this->Serie_Server->CurrentValue);
			if (strval($this->Serie_Server->CurrentValue) <> "") {
				$sFilterWrk = "`Nro_Serie`" . ew_SearchString("=", $this->Serie_Server->CurrentValue, EW_DATATYPE_STRING, "");
			$sSqlWrk = "SELECT `Nro_Serie`, `Nro_Serie` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `servidor_escolar`";
			$sWhereWrk = "";
			$this->Serie_Server->LookupFilters = array("dx1" => "`Nro_Serie`");
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Serie_Server, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->Serie_Server->EditValue = $this->Serie_Server->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->Serie_Server->EditValue = ew_HtmlEncode($this->Serie_Server->CurrentValue);
				}
			} else {
				$this->Serie_Server->EditValue = NULL;
			}
			$this->Serie_Server->PlaceHolder = ew_RemoveHtml($this->Serie_Server->FldCaption());

			// Id_Motivo
			$this->Id_Motivo->EditAttrs["class"] = "form-control";
			$this->Id_Motivo->EditCustomAttributes = "";
			if (trim(strval($this->Id_Motivo->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id_Motivo`" . ew_SearchString("=", $this->Id_Motivo->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `Id_Motivo`, `Detalle` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `motivo_pedido_paquetes`";
			$sWhereWrk = "";
			$this->Id_Motivo->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Id_Motivo, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->Id_Motivo->EditValue = $arwrk;

			// Id_Tipo_Extraccion
			$this->Id_Tipo_Extraccion->EditAttrs["class"] = "form-control";
			$this->Id_Tipo_Extraccion->EditCustomAttributes = "";
			if (trim(strval($this->Id_Tipo_Extraccion->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id_Tipo_Extraccion`" . ew_SearchString("=", $this->Id_Tipo_Extraccion->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `Id_Tipo_Extraccion`, `Detalle` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipo_extraccion`";
			$sWhereWrk = "";
			$this->Id_Tipo_Extraccion->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Id_Tipo_Extraccion, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->Id_Tipo_Extraccion->EditValue = $arwrk;

			// Id_Estado_Paquete
			$this->Id_Estado_Paquete->EditAttrs["class"] = "form-control";
			$this->Id_Estado_Paquete->EditCustomAttributes = "";
			if (trim(strval($this->Id_Estado_Paquete->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id_Estado_Paquete`" . ew_SearchString("=", $this->Id_Estado_Paquete->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `Id_Estado_Paquete`, `Detalle` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `estado_paquete`";
			$sWhereWrk = "";
			$this->Id_Estado_Paquete->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Id_Estado_Paquete, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Detalle` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->Id_Estado_Paquete->EditValue = $arwrk;

			// Id_Tipo_Paquete
			$this->Id_Tipo_Paquete->EditAttrs["class"] = "form-control";
			$this->Id_Tipo_Paquete->EditCustomAttributes = "";
			if (trim(strval($this->Id_Tipo_Paquete->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id_Tipo_Paquete`" . ew_SearchString("=", $this->Id_Tipo_Paquete->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `Id_Tipo_Paquete`, `Detalle` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipo_paquete`";
			$sWhereWrk = "";
			$this->Id_Tipo_Paquete->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Id_Tipo_Paquete, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->Id_Tipo_Paquete->EditValue = $arwrk;

			// Apellido_Nombre_Solicitante
			$this->Apellido_Nombre_Solicitante->EditAttrs["class"] = "form-control";
			$this->Apellido_Nombre_Solicitante->EditCustomAttributes = "";
			$this->Apellido_Nombre_Solicitante->EditValue = ew_HtmlEncode($this->Apellido_Nombre_Solicitante->CurrentValue);
			if (strval($this->Apellido_Nombre_Solicitante->CurrentValue) <> "") {
				$sFilterWrk = "`Apellido_Nombre`" . ew_SearchString("=", $this->Apellido_Nombre_Solicitante->CurrentValue, EW_DATATYPE_STRING, "");
			$sSqlWrk = "SELECT `Apellido_Nombre`, `Apellido_Nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `referente_tecnico`";
			$sWhereWrk = "";
			$this->Apellido_Nombre_Solicitante->LookupFilters = array("dx1" => "`Apellido_Nombre`");
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Apellido_Nombre_Solicitante, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->Apellido_Nombre_Solicitante->EditValue = $this->Apellido_Nombre_Solicitante->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->Apellido_Nombre_Solicitante->EditValue = ew_HtmlEncode($this->Apellido_Nombre_Solicitante->CurrentValue);
				}
			} else {
				$this->Apellido_Nombre_Solicitante->EditValue = NULL;
			}
			$this->Apellido_Nombre_Solicitante->PlaceHolder = ew_RemoveHtml($this->Apellido_Nombre_Solicitante->FldCaption());

			// Dni
			$this->Dni->EditAttrs["class"] = "form-control";
			$this->Dni->EditCustomAttributes = "";
			$this->Dni->EditValue = ew_HtmlEncode($this->Dni->CurrentValue);
			$this->Dni->PlaceHolder = ew_RemoveHtml($this->Dni->FldCaption());

			// Email_Solicitante
			$this->Email_Solicitante->EditAttrs["class"] = "form-control";
			$this->Email_Solicitante->EditCustomAttributes = "";
			$this->Email_Solicitante->EditValue = ew_HtmlEncode($this->Email_Solicitante->CurrentValue);
			$this->Email_Solicitante->PlaceHolder = ew_RemoveHtml($this->Email_Solicitante->FldCaption());

			// Usuario
			// Fecha_Actualizacion
			// Edit refer script
			// NroPedido

			$this->NroPedido->LinkCustomAttributes = "";
			$this->NroPedido->HrefValue = "";

			// Serie_Netbook
			$this->Serie_Netbook->LinkCustomAttributes = "";
			$this->Serie_Netbook->HrefValue = "";

			// SN
			$this->SN->LinkCustomAttributes = "";
			$this->SN->HrefValue = "";

			// Marca_Arranque
			$this->Marca_Arranque->LinkCustomAttributes = "";
			$this->Marca_Arranque->HrefValue = "";

			// Serie_Server
			$this->Serie_Server->LinkCustomAttributes = "";
			$this->Serie_Server->HrefValue = "";

			// Id_Motivo
			$this->Id_Motivo->LinkCustomAttributes = "";
			$this->Id_Motivo->HrefValue = "";

			// Id_Tipo_Extraccion
			$this->Id_Tipo_Extraccion->LinkCustomAttributes = "";
			$this->Id_Tipo_Extraccion->HrefValue = "";

			// Id_Estado_Paquete
			$this->Id_Estado_Paquete->LinkCustomAttributes = "";
			$this->Id_Estado_Paquete->HrefValue = "";

			// Id_Tipo_Paquete
			$this->Id_Tipo_Paquete->LinkCustomAttributes = "";
			$this->Id_Tipo_Paquete->HrefValue = "";

			// Apellido_Nombre_Solicitante
			$this->Apellido_Nombre_Solicitante->LinkCustomAttributes = "";
			$this->Apellido_Nombre_Solicitante->HrefValue = "";

			// Dni
			$this->Dni->LinkCustomAttributes = "";
			$this->Dni->HrefValue = "";

			// Email_Solicitante
			$this->Email_Solicitante->LinkCustomAttributes = "";
			$this->Email_Solicitante->HrefValue = "";

			// Usuario
			$this->Usuario->LinkCustomAttributes = "";
			$this->Usuario->HrefValue = "";

			// Fecha_Actualizacion
			$this->Fecha_Actualizacion->LinkCustomAttributes = "";
			$this->Fecha_Actualizacion->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";
		$lUpdateCnt = 0;
		if ($this->NroPedido->MultiUpdate == "1") $lUpdateCnt++;
		if ($this->Serie_Netbook->MultiUpdate == "1") $lUpdateCnt++;
		if ($this->SN->MultiUpdate == "1") $lUpdateCnt++;
		if ($this->Marca_Arranque->MultiUpdate == "1") $lUpdateCnt++;
		if ($this->Serie_Server->MultiUpdate == "1") $lUpdateCnt++;
		if ($this->Id_Motivo->MultiUpdate == "1") $lUpdateCnt++;
		if ($this->Id_Tipo_Extraccion->MultiUpdate == "1") $lUpdateCnt++;
		if ($this->Id_Estado_Paquete->MultiUpdate == "1") $lUpdateCnt++;
		if ($this->Id_Tipo_Paquete->MultiUpdate == "1") $lUpdateCnt++;
		if ($this->Apellido_Nombre_Solicitante->MultiUpdate == "1") $lUpdateCnt++;
		if ($this->Dni->MultiUpdate == "1") $lUpdateCnt++;
		if ($this->Email_Solicitante->MultiUpdate == "1") $lUpdateCnt++;
		if ($this->Usuario->MultiUpdate == "1") $lUpdateCnt++;
		if ($this->Fecha_Actualizacion->MultiUpdate == "1") $lUpdateCnt++;
		if ($lUpdateCnt == 0) {
			$gsFormError = $Language->Phrase("NoFieldSelected");
			return FALSE;
		}

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if ($this->Serie_Netbook->MultiUpdate <> "" && !$this->Serie_Netbook->FldIsDetailKey && !is_null($this->Serie_Netbook->FormValue) && $this->Serie_Netbook->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Serie_Netbook->FldCaption(), $this->Serie_Netbook->ReqErrMsg));
		}
		if ($this->SN->MultiUpdate <> "" && !$this->SN->FldIsDetailKey && !is_null($this->SN->FormValue) && $this->SN->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->SN->FldCaption(), $this->SN->ReqErrMsg));
		}
		if ($this->Marca_Arranque->MultiUpdate <> "" && !$this->Marca_Arranque->FldIsDetailKey && !is_null($this->Marca_Arranque->FormValue) && $this->Marca_Arranque->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Marca_Arranque->FldCaption(), $this->Marca_Arranque->ReqErrMsg));
		}
		if ($this->Serie_Server->MultiUpdate <> "" && !$this->Serie_Server->FldIsDetailKey && !is_null($this->Serie_Server->FormValue) && $this->Serie_Server->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Serie_Server->FldCaption(), $this->Serie_Server->ReqErrMsg));
		}
		if ($this->Id_Motivo->MultiUpdate <> "" && !$this->Id_Motivo->FldIsDetailKey && !is_null($this->Id_Motivo->FormValue) && $this->Id_Motivo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Id_Motivo->FldCaption(), $this->Id_Motivo->ReqErrMsg));
		}
		if ($this->Id_Tipo_Extraccion->MultiUpdate <> "" && !$this->Id_Tipo_Extraccion->FldIsDetailKey && !is_null($this->Id_Tipo_Extraccion->FormValue) && $this->Id_Tipo_Extraccion->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Id_Tipo_Extraccion->FldCaption(), $this->Id_Tipo_Extraccion->ReqErrMsg));
		}
		if ($this->Id_Estado_Paquete->MultiUpdate <> "" && !$this->Id_Estado_Paquete->FldIsDetailKey && !is_null($this->Id_Estado_Paquete->FormValue) && $this->Id_Estado_Paquete->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Id_Estado_Paquete->FldCaption(), $this->Id_Estado_Paquete->ReqErrMsg));
		}
		if ($this->Id_Tipo_Paquete->MultiUpdate <> "" && !$this->Id_Tipo_Paquete->FldIsDetailKey && !is_null($this->Id_Tipo_Paquete->FormValue) && $this->Id_Tipo_Paquete->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Id_Tipo_Paquete->FldCaption(), $this->Id_Tipo_Paquete->ReqErrMsg));
		}
		if ($this->Apellido_Nombre_Solicitante->MultiUpdate <> "" && !$this->Apellido_Nombre_Solicitante->FldIsDetailKey && !is_null($this->Apellido_Nombre_Solicitante->FormValue) && $this->Apellido_Nombre_Solicitante->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Apellido_Nombre_Solicitante->FldCaption(), $this->Apellido_Nombre_Solicitante->ReqErrMsg));
		}
		if ($this->Dni->MultiUpdate <> "" && !$this->Dni->FldIsDetailKey && !is_null($this->Dni->FormValue) && $this->Dni->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Dni->FldCaption(), $this->Dni->ReqErrMsg));
		}
		if ($this->Dni->MultiUpdate <> "") {
			if (!ew_CheckInteger($this->Dni->FormValue)) {
				ew_AddMessage($gsFormError, $this->Dni->FldErrMsg());
			}
		}
		if ($this->Email_Solicitante->MultiUpdate <> "" && !$this->Email_Solicitante->FldIsDetailKey && !is_null($this->Email_Solicitante->FormValue) && $this->Email_Solicitante->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Email_Solicitante->FldCaption(), $this->Email_Solicitante->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// Serie_Netbook
			$this->Serie_Netbook->SetDbValueDef($rsnew, $this->Serie_Netbook->CurrentValue, NULL, $this->Serie_Netbook->ReadOnly || $this->Serie_Netbook->MultiUpdate <> "1");

			// SN
			$this->SN->SetDbValueDef($rsnew, $this->SN->CurrentValue, NULL, $this->SN->ReadOnly || $this->SN->MultiUpdate <> "1");

			// Marca_Arranque
			$this->Marca_Arranque->SetDbValueDef($rsnew, $this->Marca_Arranque->CurrentValue, NULL, $this->Marca_Arranque->ReadOnly || $this->Marca_Arranque->MultiUpdate <> "1");

			// Serie_Server
			$this->Serie_Server->SetDbValueDef($rsnew, $this->Serie_Server->CurrentValue, "", $this->Serie_Server->ReadOnly || $this->Serie_Server->MultiUpdate <> "1");

			// Id_Motivo
			$this->Id_Motivo->SetDbValueDef($rsnew, $this->Id_Motivo->CurrentValue, 0, $this->Id_Motivo->ReadOnly || $this->Id_Motivo->MultiUpdate <> "1");

			// Id_Tipo_Extraccion
			$this->Id_Tipo_Extraccion->SetDbValueDef($rsnew, $this->Id_Tipo_Extraccion->CurrentValue, 0, $this->Id_Tipo_Extraccion->ReadOnly || $this->Id_Tipo_Extraccion->MultiUpdate <> "1");

			// Id_Estado_Paquete
			$this->Id_Estado_Paquete->SetDbValueDef($rsnew, $this->Id_Estado_Paquete->CurrentValue, 0, $this->Id_Estado_Paquete->ReadOnly || $this->Id_Estado_Paquete->MultiUpdate <> "1");

			// Id_Tipo_Paquete
			$this->Id_Tipo_Paquete->SetDbValueDef($rsnew, $this->Id_Tipo_Paquete->CurrentValue, NULL, $this->Id_Tipo_Paquete->ReadOnly || $this->Id_Tipo_Paquete->MultiUpdate <> "1");

			// Apellido_Nombre_Solicitante
			$this->Apellido_Nombre_Solicitante->SetDbValueDef($rsnew, $this->Apellido_Nombre_Solicitante->CurrentValue, NULL, $this->Apellido_Nombre_Solicitante->ReadOnly || $this->Apellido_Nombre_Solicitante->MultiUpdate <> "1");

			// Dni
			$this->Dni->SetDbValueDef($rsnew, $this->Dni->CurrentValue, NULL, $this->Dni->ReadOnly || $this->Dni->MultiUpdate <> "1");

			// Email_Solicitante
			$this->Email_Solicitante->SetDbValueDef($rsnew, $this->Email_Solicitante->CurrentValue, NULL, $this->Email_Solicitante->ReadOnly || $this->Email_Solicitante->MultiUpdate <> "1");

			// Usuario
			$this->Usuario->SetDbValueDef($rsnew, CurrentUserName(), NULL);
			$rsnew['Usuario'] = &$this->Usuario->DbValue;

			// Fecha_Actualizacion
			$this->Fecha_Actualizacion->SetDbValueDef($rsnew, ew_CurrentDate(), NULL);
			$rsnew['Fecha_Actualizacion'] = &$this->Fecha_Actualizacion->DbValue;

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		if ($EditRow) {
			$this->WriteAuditTrailOnEdit($rsold, $rsnew);
		}
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("paquetes_provisionlist.php"), "", $this->TableVar, TRUE);
		$PageId = "update";
		$Breadcrumb->Add("update", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_Serie_Netbook":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `NroSerie` AS `LinkFld`, `NroSerie` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `equipos`";
			$sWhereWrk = "{filter}";
			$this->Serie_Netbook->LookupFilters = array("dx1" => "`NroSerie`");
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`NroSerie` = {filter_value}", "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Serie_Netbook, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_Serie_Server":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Nro_Serie` AS `LinkFld`, `Nro_Serie` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `servidor_escolar`";
			$sWhereWrk = "{filter}";
			$this->Serie_Server->LookupFilters = array("dx1" => "`Nro_Serie`");
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`Nro_Serie` = {filter_value}", "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Serie_Server, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_Id_Motivo":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Id_Motivo` AS `LinkFld`, `Detalle` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `motivo_pedido_paquetes`";
			$sWhereWrk = "";
			$this->Id_Motivo->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`Id_Motivo` = {filter_value}", "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Id_Motivo, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_Id_Tipo_Extraccion":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Id_Tipo_Extraccion` AS `LinkFld`, `Detalle` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_extraccion`";
			$sWhereWrk = "";
			$this->Id_Tipo_Extraccion->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`Id_Tipo_Extraccion` = {filter_value}", "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Id_Tipo_Extraccion, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_Id_Estado_Paquete":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Id_Estado_Paquete` AS `LinkFld`, `Detalle` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `estado_paquete`";
			$sWhereWrk = "";
			$this->Id_Estado_Paquete->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`Id_Estado_Paquete` = {filter_value}", "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Id_Estado_Paquete, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Detalle` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_Id_Tipo_Paquete":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Id_Tipo_Paquete` AS `LinkFld`, `Detalle` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_paquete`";
			$sWhereWrk = "";
			$this->Id_Tipo_Paquete->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`Id_Tipo_Paquete` = {filter_value}", "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Id_Tipo_Paquete, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_Apellido_Nombre_Solicitante":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Apellido_Nombre` AS `LinkFld`, `Apellido_Nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `referente_tecnico`";
			$sWhereWrk = "{filter}";
			$this->Apellido_Nombre_Solicitante->LookupFilters = array("dx1" => "`Apellido_Nombre`");
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`Apellido_Nombre` = {filter_value}", "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Apellido_Nombre_Solicitante, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_Serie_Netbook":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `NroSerie`, `NroSerie` AS `DispFld` FROM `equipos`";
			$sWhereWrk = "`NroSerie` LIKE '{query_value}%'";
			$this->Serie_Netbook->LookupFilters = array("dx1" => "`NroSerie`");
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Serie_Netbook, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_Serie_Server":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Nro_Serie`, `Nro_Serie` AS `DispFld` FROM `servidor_escolar`";
			$sWhereWrk = "`Nro_Serie` LIKE '{query_value}%'";
			$this->Serie_Server->LookupFilters = array("dx1" => "`Nro_Serie`");
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Serie_Server, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_Apellido_Nombre_Solicitante":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Apellido_Nombre`, `Apellido_Nombre` AS `DispFld` FROM `referente_tecnico`";
			$sWhereWrk = "`Apellido_Nombre` LIKE '{query_value}%'";
			$this->Apellido_Nombre_Solicitante->LookupFilters = array("dx1" => "`Apellido_Nombre`");
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Apellido_Nombre_Solicitante, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		}
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'paquetes_provision';
		$usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		global $Language;
		if (!$this->AuditTrailOnEdit) return;
		$table = 'paquetes_provision';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['NroPedido'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$usr = CurrentUserID();
		foreach (array_keys($rsnew) as $fldname) {
			if ($this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_DATE) { // DateTime field
					$modified = (ew_FormatDateTime($rsold[$fldname], 0) <> ew_FormatDateTime($rsnew[$fldname], 0));
				} else {
					$modified = !ew_CompareValue($rsold[$fldname], $rsnew[$fldname]);
				}
				if ($modified) {
					if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") { // Password Field
						$oldvalue = $Language->Phrase("PasswordMask");
						$newvalue = $Language->Phrase("PasswordMask");
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) { // Memo field
						if (EW_AUDIT_TRAIL_TO_DATABASE) {
							$oldvalue = $rsold[$fldname];
							$newvalue = $rsnew[$fldname];
						} else {
							$oldvalue = "[MEMO]";
							$newvalue = "[MEMO]";
						}
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) { // XML field
						$oldvalue = "[XML]";
						$newvalue = "[XML]";
					} else {
						$oldvalue = $rsold[$fldname];
						$newvalue = $rsnew[$fldname];
					}
					ew_WriteAuditTrail("log", $dt, $id, $usr, "U", $table, $fldname, $key, $oldvalue, $newvalue);
				}
			}
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(TRUE) ?>
<?php

// Create page object
if (!isset($paquetes_provision_update)) $paquetes_provision_update = new cpaquetes_provision_update();

// Page init
$paquetes_provision_update->Page_Init();

// Page main
$paquetes_provision_update->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$paquetes_provision_update->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "update";
var CurrentForm = fpaquetes_provisionupdate = new ew_Form("fpaquetes_provisionupdate", "update");

// Validate form
fpaquetes_provisionupdate.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	if (!ew_UpdateSelected(fobj)) {
		ew_Alert(ewLanguage.Phrase("NoFieldSelected"));
		return false;
	}
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_Serie_Netbook");
			uelm = this.GetElements("u" + infix + "_Serie_Netbook");
			if (uelm && uelm.checked) {
				if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
					return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $paquetes_provision->Serie_Netbook->FldCaption(), $paquetes_provision->Serie_Netbook->ReqErrMsg)) ?>");
			}
			elm = this.GetElements("x" + infix + "_SN");
			uelm = this.GetElements("u" + infix + "_SN");
			if (uelm && uelm.checked) {
				if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
					return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $paquetes_provision->SN->FldCaption(), $paquetes_provision->SN->ReqErrMsg)) ?>");
			}
			elm = this.GetElements("x" + infix + "_Marca_Arranque");
			uelm = this.GetElements("u" + infix + "_Marca_Arranque");
			if (uelm && uelm.checked) {
				if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
					return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $paquetes_provision->Marca_Arranque->FldCaption(), $paquetes_provision->Marca_Arranque->ReqErrMsg)) ?>");
			}
			elm = this.GetElements("x" + infix + "_Serie_Server");
			uelm = this.GetElements("u" + infix + "_Serie_Server");
			if (uelm && uelm.checked) {
				if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
					return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $paquetes_provision->Serie_Server->FldCaption(), $paquetes_provision->Serie_Server->ReqErrMsg)) ?>");
			}
			elm = this.GetElements("x" + infix + "_Id_Motivo");
			uelm = this.GetElements("u" + infix + "_Id_Motivo");
			if (uelm && uelm.checked) {
				if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
					return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $paquetes_provision->Id_Motivo->FldCaption(), $paquetes_provision->Id_Motivo->ReqErrMsg)) ?>");
			}
			elm = this.GetElements("x" + infix + "_Id_Tipo_Extraccion");
			uelm = this.GetElements("u" + infix + "_Id_Tipo_Extraccion");
			if (uelm && uelm.checked) {
				if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
					return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $paquetes_provision->Id_Tipo_Extraccion->FldCaption(), $paquetes_provision->Id_Tipo_Extraccion->ReqErrMsg)) ?>");
			}
			elm = this.GetElements("x" + infix + "_Id_Estado_Paquete");
			uelm = this.GetElements("u" + infix + "_Id_Estado_Paquete");
			if (uelm && uelm.checked) {
				if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
					return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $paquetes_provision->Id_Estado_Paquete->FldCaption(), $paquetes_provision->Id_Estado_Paquete->ReqErrMsg)) ?>");
			}
			elm = this.GetElements("x" + infix + "_Id_Tipo_Paquete");
			uelm = this.GetElements("u" + infix + "_Id_Tipo_Paquete");
			if (uelm && uelm.checked) {
				if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
					return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $paquetes_provision->Id_Tipo_Paquete->FldCaption(), $paquetes_provision->Id_Tipo_Paquete->ReqErrMsg)) ?>");
			}
			elm = this.GetElements("x" + infix + "_Apellido_Nombre_Solicitante");
			uelm = this.GetElements("u" + infix + "_Apellido_Nombre_Solicitante");
			if (uelm && uelm.checked) {
				if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
					return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $paquetes_provision->Apellido_Nombre_Solicitante->FldCaption(), $paquetes_provision->Apellido_Nombre_Solicitante->ReqErrMsg)) ?>");
			}
			elm = this.GetElements("x" + infix + "_Dni");
			uelm = this.GetElements("u" + infix + "_Dni");
			if (uelm && uelm.checked) {
				if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
					return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $paquetes_provision->Dni->FldCaption(), $paquetes_provision->Dni->ReqErrMsg)) ?>");
			}
			elm = this.GetElements("x" + infix + "_Dni");
			uelm = this.GetElements("u" + infix + "_Dni");
			if (uelm && uelm.checked && elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($paquetes_provision->Dni->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Email_Solicitante");
			uelm = this.GetElements("u" + infix + "_Email_Solicitante");
			if (uelm && uelm.checked) {
				if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
					return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $paquetes_provision->Email_Solicitante->FldCaption(), $paquetes_provision->Email_Solicitante->ReqErrMsg)) ?>");
			}

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fpaquetes_provisionupdate.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpaquetes_provisionupdate.ValidateRequired = true;
<?php } else { ?>
fpaquetes_provisionupdate.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpaquetes_provisionupdate.Lists["x_Serie_Netbook"] = {"LinkField":"x_NroSerie","Ajax":true,"AutoFill":true,"DisplayFields":["x_NroSerie","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"equipos"};
fpaquetes_provisionupdate.Lists["x_Serie_Server"] = {"LinkField":"x_Nro_Serie","Ajax":true,"AutoFill":false,"DisplayFields":["x_Nro_Serie","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"servidor_escolar"};
fpaquetes_provisionupdate.Lists["x_Id_Motivo"] = {"LinkField":"x_Id_Motivo","Ajax":true,"AutoFill":false,"DisplayFields":["x_Detalle","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"motivo_pedido_paquetes"};
fpaquetes_provisionupdate.Lists["x_Id_Tipo_Extraccion"] = {"LinkField":"x_Id_Tipo_Extraccion","Ajax":true,"AutoFill":false,"DisplayFields":["x_Detalle","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipo_extraccion"};
fpaquetes_provisionupdate.Lists["x_Id_Estado_Paquete"] = {"LinkField":"x_Id_Estado_Paquete","Ajax":true,"AutoFill":false,"DisplayFields":["x_Detalle","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"estado_paquete"};
fpaquetes_provisionupdate.Lists["x_Id_Tipo_Paquete"] = {"LinkField":"x_Id_Tipo_Paquete","Ajax":true,"AutoFill":false,"DisplayFields":["x_Detalle","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipo_paquete"};
fpaquetes_provisionupdate.Lists["x_Apellido_Nombre_Solicitante"] = {"LinkField":"x_Apellido_Nombre","Ajax":true,"AutoFill":true,"DisplayFields":["x_Apellido_Nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"referente_tecnico"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$paquetes_provision_update->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $paquetes_provision_update->ShowPageHeader(); ?>
<?php
$paquetes_provision_update->ShowMessage();
?>
<form name="fpaquetes_provisionupdate" id="fpaquetes_provisionupdate" class="<?php echo $paquetes_provision_update->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($paquetes_provision_update->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $paquetes_provision_update->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="paquetes_provision">
<input type="hidden" name="a_update" id="a_update" value="U">
<?php if ($paquetes_provision_update->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<?php foreach ($paquetes_provision_update->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div id="tbl_paquetes_provisionupdate">
	<div class="checkbox">
		<label><input type="checkbox" name="u" id="u" onclick="ew_SelectAll(this);"> <?php echo $Language->Phrase("UpdateSelectAll") ?></label>
	</div>
<?php if ($paquetes_provision->Serie_Netbook->Visible) { // Serie_Netbook ?>
	<div id="r_Serie_Netbook" class="form-group">
		<label class="col-sm-2 control-label">
<input type="checkbox" name="u_Serie_Netbook" id="u_Serie_Netbook" value="1"<?php echo ($paquetes_provision->Serie_Netbook->MultiUpdate == "1") ? " checked" : "" ?>>
 <?php echo $paquetes_provision->Serie_Netbook->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $paquetes_provision->Serie_Netbook->CellAttributes() ?>>
<span id="el_paquetes_provision_Serie_Netbook">
<?php $paquetes_provision->Serie_Netbook->EditAttrs["onchange"] = "ew_AutoFill(this); " . @$paquetes_provision->Serie_Netbook->EditAttrs["onchange"]; ?>
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_Serie_Netbook"><?php echo (strval($paquetes_provision->Serie_Netbook->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $paquetes_provision->Serie_Netbook->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($paquetes_provision->Serie_Netbook->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_Serie_Netbook',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="paquetes_provision" data-field="x_Serie_Netbook" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $paquetes_provision->Serie_Netbook->DisplayValueSeparatorAttribute() ?>" name="x_Serie_Netbook" id="x_Serie_Netbook" value="<?php echo $paquetes_provision->Serie_Netbook->CurrentValue ?>"<?php echo $paquetes_provision->Serie_Netbook->EditAttributes() ?>>
<input type="hidden" name="s_x_Serie_Netbook" id="s_x_Serie_Netbook" value="<?php echo $paquetes_provision->Serie_Netbook->LookupFilterQuery() ?>">
<input type="hidden" name="ln_x_Serie_Netbook" id="ln_x_Serie_Netbook" value="x_SN">
</span>
<?php echo $paquetes_provision->Serie_Netbook->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($paquetes_provision->SN->Visible) { // SN ?>
	<div id="r_SN" class="form-group">
		<label for="x_SN" class="col-sm-2 control-label">
<input type="checkbox" name="u_SN" id="u_SN" value="1"<?php echo ($paquetes_provision->SN->MultiUpdate == "1") ? " checked" : "" ?>>
 <?php echo $paquetes_provision->SN->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $paquetes_provision->SN->CellAttributes() ?>>
<span id="el_paquetes_provision_SN">
<input type="text" data-table="paquetes_provision" data-field="x_SN" name="x_SN" id="x_SN" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($paquetes_provision->SN->getPlaceHolder()) ?>" value="<?php echo $paquetes_provision->SN->EditValue ?>"<?php echo $paquetes_provision->SN->EditAttributes() ?>>
</span>
<?php echo $paquetes_provision->SN->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($paquetes_provision->Marca_Arranque->Visible) { // Marca_Arranque ?>
	<div id="r_Marca_Arranque" class="form-group">
		<label for="x_Marca_Arranque" class="col-sm-2 control-label">
<input type="checkbox" name="u_Marca_Arranque" id="u_Marca_Arranque" value="1"<?php echo ($paquetes_provision->Marca_Arranque->MultiUpdate == "1") ? " checked" : "" ?>>
 <?php echo $paquetes_provision->Marca_Arranque->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $paquetes_provision->Marca_Arranque->CellAttributes() ?>>
<span id="el_paquetes_provision_Marca_Arranque">
<input type="text" data-table="paquetes_provision" data-field="x_Marca_Arranque" name="x_Marca_Arranque" id="x_Marca_Arranque" size="10" maxlength="5" placeholder="<?php echo ew_HtmlEncode($paquetes_provision->Marca_Arranque->getPlaceHolder()) ?>" value="<?php echo $paquetes_provision->Marca_Arranque->EditValue ?>"<?php echo $paquetes_provision->Marca_Arranque->EditAttributes() ?>>
</span>
<?php echo $paquetes_provision->Marca_Arranque->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($paquetes_provision->Serie_Server->Visible) { // Serie_Server ?>
	<div id="r_Serie_Server" class="form-group">
		<label class="col-sm-2 control-label">
<input type="checkbox" name="u_Serie_Server" id="u_Serie_Server" value="1"<?php echo ($paquetes_provision->Serie_Server->MultiUpdate == "1") ? " checked" : "" ?>>
 <?php echo $paquetes_provision->Serie_Server->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $paquetes_provision->Serie_Server->CellAttributes() ?>>
<span id="el_paquetes_provision_Serie_Server">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_Serie_Server"><?php echo (strval($paquetes_provision->Serie_Server->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $paquetes_provision->Serie_Server->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($paquetes_provision->Serie_Server->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_Serie_Server',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="paquetes_provision" data-field="x_Serie_Server" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $paquetes_provision->Serie_Server->DisplayValueSeparatorAttribute() ?>" name="x_Serie_Server" id="x_Serie_Server" value="<?php echo $paquetes_provision->Serie_Server->CurrentValue ?>"<?php echo $paquetes_provision->Serie_Server->EditAttributes() ?>>
<input type="hidden" name="s_x_Serie_Server" id="s_x_Serie_Server" value="<?php echo $paquetes_provision->Serie_Server->LookupFilterQuery() ?>">
</span>
<?php echo $paquetes_provision->Serie_Server->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($paquetes_provision->Id_Motivo->Visible) { // Id_Motivo ?>
	<div id="r_Id_Motivo" class="form-group">
		<label for="x_Id_Motivo" class="col-sm-2 control-label">
<input type="checkbox" name="u_Id_Motivo" id="u_Id_Motivo" value="1"<?php echo ($paquetes_provision->Id_Motivo->MultiUpdate == "1") ? " checked" : "" ?>>
 <?php echo $paquetes_provision->Id_Motivo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $paquetes_provision->Id_Motivo->CellAttributes() ?>>
<span id="el_paquetes_provision_Id_Motivo">
<select data-table="paquetes_provision" data-field="x_Id_Motivo" data-value-separator="<?php echo $paquetes_provision->Id_Motivo->DisplayValueSeparatorAttribute() ?>" id="x_Id_Motivo" name="x_Id_Motivo"<?php echo $paquetes_provision->Id_Motivo->EditAttributes() ?>>
<?php echo $paquetes_provision->Id_Motivo->SelectOptionListHtml("x_Id_Motivo") ?>
</select>
<input type="hidden" name="s_x_Id_Motivo" id="s_x_Id_Motivo" value="<?php echo $paquetes_provision->Id_Motivo->LookupFilterQuery() ?>">
</span>
<?php echo $paquetes_provision->Id_Motivo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($paquetes_provision->Id_Tipo_Extraccion->Visible) { // Id_Tipo_Extraccion ?>
	<div id="r_Id_Tipo_Extraccion" class="form-group">
		<label for="x_Id_Tipo_Extraccion" class="col-sm-2 control-label">
<input type="checkbox" name="u_Id_Tipo_Extraccion" id="u_Id_Tipo_Extraccion" value="1"<?php echo ($paquetes_provision->Id_Tipo_Extraccion->MultiUpdate == "1") ? " checked" : "" ?>>
 <?php echo $paquetes_provision->Id_Tipo_Extraccion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $paquetes_provision->Id_Tipo_Extraccion->CellAttributes() ?>>
<span id="el_paquetes_provision_Id_Tipo_Extraccion">
<select data-table="paquetes_provision" data-field="x_Id_Tipo_Extraccion" data-value-separator="<?php echo $paquetes_provision->Id_Tipo_Extraccion->DisplayValueSeparatorAttribute() ?>" id="x_Id_Tipo_Extraccion" name="x_Id_Tipo_Extraccion"<?php echo $paquetes_provision->Id_Tipo_Extraccion->EditAttributes() ?>>
<?php echo $paquetes_provision->Id_Tipo_Extraccion->SelectOptionListHtml("x_Id_Tipo_Extraccion") ?>
</select>
<input type="hidden" name="s_x_Id_Tipo_Extraccion" id="s_x_Id_Tipo_Extraccion" value="<?php echo $paquetes_provision->Id_Tipo_Extraccion->LookupFilterQuery() ?>">
</span>
<?php echo $paquetes_provision->Id_Tipo_Extraccion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($paquetes_provision->Id_Estado_Paquete->Visible) { // Id_Estado_Paquete ?>
	<div id="r_Id_Estado_Paquete" class="form-group">
		<label for="x_Id_Estado_Paquete" class="col-sm-2 control-label">
<input type="checkbox" name="u_Id_Estado_Paquete" id="u_Id_Estado_Paquete" value="1"<?php echo ($paquetes_provision->Id_Estado_Paquete->MultiUpdate == "1") ? " checked" : "" ?>>
 <?php echo $paquetes_provision->Id_Estado_Paquete->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $paquetes_provision->Id_Estado_Paquete->CellAttributes() ?>>
<span id="el_paquetes_provision_Id_Estado_Paquete">
<select data-table="paquetes_provision" data-field="x_Id_Estado_Paquete" data-value-separator="<?php echo $paquetes_provision->Id_Estado_Paquete->DisplayValueSeparatorAttribute() ?>" id="x_Id_Estado_Paquete" name="x_Id_Estado_Paquete"<?php echo $paquetes_provision->Id_Estado_Paquete->EditAttributes() ?>>
<?php echo $paquetes_provision->Id_Estado_Paquete->SelectOptionListHtml("x_Id_Estado_Paquete") ?>
</select>
<input type="hidden" name="s_x_Id_Estado_Paquete" id="s_x_Id_Estado_Paquete" value="<?php echo $paquetes_provision->Id_Estado_Paquete->LookupFilterQuery() ?>">
</span>
<?php echo $paquetes_provision->Id_Estado_Paquete->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($paquetes_provision->Id_Tipo_Paquete->Visible) { // Id_Tipo_Paquete ?>
	<div id="r_Id_Tipo_Paquete" class="form-group">
		<label for="x_Id_Tipo_Paquete" class="col-sm-2 control-label">
<input type="checkbox" name="u_Id_Tipo_Paquete" id="u_Id_Tipo_Paquete" value="1"<?php echo ($paquetes_provision->Id_Tipo_Paquete->MultiUpdate == "1") ? " checked" : "" ?>>
 <?php echo $paquetes_provision->Id_Tipo_Paquete->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $paquetes_provision->Id_Tipo_Paquete->CellAttributes() ?>>
<span id="el_paquetes_provision_Id_Tipo_Paquete">
<select data-table="paquetes_provision" data-field="x_Id_Tipo_Paquete" data-value-separator="<?php echo $paquetes_provision->Id_Tipo_Paquete->DisplayValueSeparatorAttribute() ?>" id="x_Id_Tipo_Paquete" name="x_Id_Tipo_Paquete"<?php echo $paquetes_provision->Id_Tipo_Paquete->EditAttributes() ?>>
<?php echo $paquetes_provision->Id_Tipo_Paquete->SelectOptionListHtml("x_Id_Tipo_Paquete") ?>
</select>
<input type="hidden" name="s_x_Id_Tipo_Paquete" id="s_x_Id_Tipo_Paquete" value="<?php echo $paquetes_provision->Id_Tipo_Paquete->LookupFilterQuery() ?>">
</span>
<?php echo $paquetes_provision->Id_Tipo_Paquete->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($paquetes_provision->Apellido_Nombre_Solicitante->Visible) { // Apellido_Nombre_Solicitante ?>
	<div id="r_Apellido_Nombre_Solicitante" class="form-group">
		<label class="col-sm-2 control-label">
<input type="checkbox" name="u_Apellido_Nombre_Solicitante" id="u_Apellido_Nombre_Solicitante" value="1"<?php echo ($paquetes_provision->Apellido_Nombre_Solicitante->MultiUpdate == "1") ? " checked" : "" ?>>
 <?php echo $paquetes_provision->Apellido_Nombre_Solicitante->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $paquetes_provision->Apellido_Nombre_Solicitante->CellAttributes() ?>>
<span id="el_paquetes_provision_Apellido_Nombre_Solicitante">
<?php $paquetes_provision->Apellido_Nombre_Solicitante->EditAttrs["onchange"] = "ew_AutoFill(this); " . @$paquetes_provision->Apellido_Nombre_Solicitante->EditAttrs["onchange"]; ?>
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_Apellido_Nombre_Solicitante"><?php echo (strval($paquetes_provision->Apellido_Nombre_Solicitante->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $paquetes_provision->Apellido_Nombre_Solicitante->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($paquetes_provision->Apellido_Nombre_Solicitante->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_Apellido_Nombre_Solicitante',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="paquetes_provision" data-field="x_Apellido_Nombre_Solicitante" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $paquetes_provision->Apellido_Nombre_Solicitante->DisplayValueSeparatorAttribute() ?>" name="x_Apellido_Nombre_Solicitante" id="x_Apellido_Nombre_Solicitante" value="<?php echo $paquetes_provision->Apellido_Nombre_Solicitante->CurrentValue ?>"<?php echo $paquetes_provision->Apellido_Nombre_Solicitante->EditAttributes() ?>>
<input type="hidden" name="s_x_Apellido_Nombre_Solicitante" id="s_x_Apellido_Nombre_Solicitante" value="<?php echo $paquetes_provision->Apellido_Nombre_Solicitante->LookupFilterQuery() ?>">
<input type="hidden" name="ln_x_Apellido_Nombre_Solicitante" id="ln_x_Apellido_Nombre_Solicitante" value="x_Email_Solicitante,x_Dni">
</span>
<?php echo $paquetes_provision->Apellido_Nombre_Solicitante->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($paquetes_provision->Dni->Visible) { // Dni ?>
	<div id="r_Dni" class="form-group">
		<label for="x_Dni" class="col-sm-2 control-label">
<input type="checkbox" name="u_Dni" id="u_Dni" value="1"<?php echo ($paquetes_provision->Dni->MultiUpdate == "1") ? " checked" : "" ?>>
 <?php echo $paquetes_provision->Dni->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $paquetes_provision->Dni->CellAttributes() ?>>
<span id="el_paquetes_provision_Dni">
<input type="text" data-table="paquetes_provision" data-field="x_Dni" name="x_Dni" id="x_Dni" size="30" placeholder="<?php echo ew_HtmlEncode($paquetes_provision->Dni->getPlaceHolder()) ?>" value="<?php echo $paquetes_provision->Dni->EditValue ?>"<?php echo $paquetes_provision->Dni->EditAttributes() ?>>
</span>
<?php echo $paquetes_provision->Dni->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($paquetes_provision->Email_Solicitante->Visible) { // Email_Solicitante ?>
	<div id="r_Email_Solicitante" class="form-group">
		<label for="x_Email_Solicitante" class="col-sm-2 control-label">
<input type="checkbox" name="u_Email_Solicitante" id="u_Email_Solicitante" value="1"<?php echo ($paquetes_provision->Email_Solicitante->MultiUpdate == "1") ? " checked" : "" ?>>
 <?php echo $paquetes_provision->Email_Solicitante->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $paquetes_provision->Email_Solicitante->CellAttributes() ?>>
<span id="el_paquetes_provision_Email_Solicitante">
<input type="text" data-table="paquetes_provision" data-field="x_Email_Solicitante" name="x_Email_Solicitante" id="x_Email_Solicitante" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($paquetes_provision->Email_Solicitante->getPlaceHolder()) ?>" value="<?php echo $paquetes_provision->Email_Solicitante->EditValue ?>"<?php echo $paquetes_provision->Email_Solicitante->EditAttributes() ?>>
</span>
<?php echo $paquetes_provision->Email_Solicitante->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if (!$paquetes_provision_update->IsModal) { ?>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("UpdateBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $paquetes_provision_update->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
		</div>
	</div>
<?php } ?>
</div>
</form>
<script type="text/javascript">
fpaquetes_provisionupdate.Init();
</script>
<?php
$paquetes_provision_update->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$paquetes_provision_update->Page_Terminate();
?>
