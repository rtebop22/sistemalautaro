<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "pase_establecimientoinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$pase_establecimiento_add = NULL; // Initialize page object first

class cpase_establecimiento_add extends cpase_establecimiento {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{9FD9BA28-0339-4B41-9A45-0CAE935EFE3A}";

	// Table name
	var $TableName = 'pase_establecimiento';

	// Page object name
	var $PageObjName = 'pase_establecimiento_add';

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
	var $AuditTrailOnAdd = TRUE;
	var $AuditTrailOnEdit = FALSE;
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

		// Table object (pase_establecimiento)
		if (!isset($GLOBALS["pase_establecimiento"]) || get_class($GLOBALS["pase_establecimiento"]) == "cpase_establecimiento") {
			$GLOBALS["pase_establecimiento"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pase_establecimiento"];
		}

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'pase_establecimiento', TRUE);

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
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("pase_establecimientolist.php"));
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
		$this->Serie_Equipo->SetVisibility();
		$this->Id_Hardware->SetVisibility();
		$this->SN->SetVisibility();
		$this->Modelo_Net->SetVisibility();
		$this->Marca_Arranque->SetVisibility();
		$this->Nombre_Titular->SetVisibility();
		$this->Dni_Titular->SetVisibility();
		$this->Cuil_Titular->SetVisibility();
		$this->Nombre_Tutor->SetVisibility();
		$this->DniTutor->SetVisibility();
		$this->Domicilio->SetVisibility();
		$this->Tel_Tutor->SetVisibility();
		$this->CelTutor->SetVisibility();
		$this->Cue_Establecimiento_Alta->SetVisibility();
		$this->Escuela_Alta->SetVisibility();
		$this->Directivo_Alta->SetVisibility();
		$this->Cuil_Directivo_Alta->SetVisibility();
		$this->Dpto_Esc_alta->SetVisibility();
		$this->Localidad_Esc_Alta->SetVisibility();
		$this->Domicilio_Esc_Alta->SetVisibility();
		$this->Rte_Alta->SetVisibility();
		$this->Tel_Rte_Alta->SetVisibility();
		$this->Email_Rte_Alta->SetVisibility();
		$this->Serie_Server_Alta->SetVisibility();
		$this->Cue_Establecimiento_Baja->SetVisibility();
		$this->Escuela_Baja->SetVisibility();
		$this->Directivo_Baja->SetVisibility();
		$this->Cuil_Directivo_Baja->SetVisibility();
		$this->Dpto_Esc_Baja->SetVisibility();
		$this->Localidad_Esc_Baja->SetVisibility();
		$this->Domicilio_Esc_Baja->SetVisibility();
		$this->Rte_Baja->SetVisibility();
		$this->Tel_Rte_Baja->SetVisibility();
		$this->Email_Rte_Baja->SetVisibility();
		$this->Serie_Server_Baja->SetVisibility();
		$this->Fecha_Pase->SetVisibility();
		$this->Id_Estado_Pase->SetVisibility();
		$this->Ruta_Archivo->SetVisibility();

		// Set up multi page object
		$this->SetupMultiPages();

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
		global $EW_EXPORT, $pase_establecimiento;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($pase_establecimiento);
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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;
	var $MultiPages; // Multi pages object

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

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["Id_Pase"] != "") {
				$this->Id_Pase->setQueryStringValue($_GET["Id_Pase"]);
				$this->setKey("Id_Pase", $this->Id_Pase->CurrentValue); // Set up key
			} else {
				$this->setKey("Id_Pase", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("pase_establecimientolist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->GetViewUrl();
					if (ew_GetPageName($sReturnUrl) == "pase_establecimientolist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "pase_establecimientoview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->Ruta_Archivo->Upload->Index = $objForm->Index;
		$this->Ruta_Archivo->Upload->UploadFile();
		$this->Ruta_Archivo->CurrentValue = $this->Ruta_Archivo->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->Serie_Equipo->CurrentValue = NULL;
		$this->Serie_Equipo->OldValue = $this->Serie_Equipo->CurrentValue;
		$this->Id_Hardware->CurrentValue = NULL;
		$this->Id_Hardware->OldValue = $this->Id_Hardware->CurrentValue;
		$this->SN->CurrentValue = NULL;
		$this->SN->OldValue = $this->SN->CurrentValue;
		$this->Modelo_Net->CurrentValue = NULL;
		$this->Modelo_Net->OldValue = $this->Modelo_Net->CurrentValue;
		$this->Marca_Arranque->CurrentValue = NULL;
		$this->Marca_Arranque->OldValue = $this->Marca_Arranque->CurrentValue;
		$this->Nombre_Titular->CurrentValue = NULL;
		$this->Nombre_Titular->OldValue = $this->Nombre_Titular->CurrentValue;
		$this->Dni_Titular->CurrentValue = NULL;
		$this->Dni_Titular->OldValue = $this->Dni_Titular->CurrentValue;
		$this->Cuil_Titular->CurrentValue = NULL;
		$this->Cuil_Titular->OldValue = $this->Cuil_Titular->CurrentValue;
		$this->Nombre_Tutor->CurrentValue = NULL;
		$this->Nombre_Tutor->OldValue = $this->Nombre_Tutor->CurrentValue;
		$this->DniTutor->CurrentValue = NULL;
		$this->DniTutor->OldValue = $this->DniTutor->CurrentValue;
		$this->Domicilio->CurrentValue = NULL;
		$this->Domicilio->OldValue = $this->Domicilio->CurrentValue;
		$this->Tel_Tutor->CurrentValue = NULL;
		$this->Tel_Tutor->OldValue = $this->Tel_Tutor->CurrentValue;
		$this->CelTutor->CurrentValue = NULL;
		$this->CelTutor->OldValue = $this->CelTutor->CurrentValue;
		$this->Cue_Establecimiento_Alta->CurrentValue = NULL;
		$this->Cue_Establecimiento_Alta->OldValue = $this->Cue_Establecimiento_Alta->CurrentValue;
		$this->Escuela_Alta->CurrentValue = NULL;
		$this->Escuela_Alta->OldValue = $this->Escuela_Alta->CurrentValue;
		$this->Directivo_Alta->CurrentValue = NULL;
		$this->Directivo_Alta->OldValue = $this->Directivo_Alta->CurrentValue;
		$this->Cuil_Directivo_Alta->CurrentValue = NULL;
		$this->Cuil_Directivo_Alta->OldValue = $this->Cuil_Directivo_Alta->CurrentValue;
		$this->Dpto_Esc_alta->CurrentValue = NULL;
		$this->Dpto_Esc_alta->OldValue = $this->Dpto_Esc_alta->CurrentValue;
		$this->Localidad_Esc_Alta->CurrentValue = NULL;
		$this->Localidad_Esc_Alta->OldValue = $this->Localidad_Esc_Alta->CurrentValue;
		$this->Domicilio_Esc_Alta->CurrentValue = NULL;
		$this->Domicilio_Esc_Alta->OldValue = $this->Domicilio_Esc_Alta->CurrentValue;
		$this->Rte_Alta->CurrentValue = NULL;
		$this->Rte_Alta->OldValue = $this->Rte_Alta->CurrentValue;
		$this->Tel_Rte_Alta->CurrentValue = NULL;
		$this->Tel_Rte_Alta->OldValue = $this->Tel_Rte_Alta->CurrentValue;
		$this->Email_Rte_Alta->CurrentValue = NULL;
		$this->Email_Rte_Alta->OldValue = $this->Email_Rte_Alta->CurrentValue;
		$this->Serie_Server_Alta->CurrentValue = NULL;
		$this->Serie_Server_Alta->OldValue = $this->Serie_Server_Alta->CurrentValue;
		$this->Cue_Establecimiento_Baja->CurrentValue = NULL;
		$this->Cue_Establecimiento_Baja->OldValue = $this->Cue_Establecimiento_Baja->CurrentValue;
		$this->Escuela_Baja->CurrentValue = NULL;
		$this->Escuela_Baja->OldValue = $this->Escuela_Baja->CurrentValue;
		$this->Directivo_Baja->CurrentValue = NULL;
		$this->Directivo_Baja->OldValue = $this->Directivo_Baja->CurrentValue;
		$this->Cuil_Directivo_Baja->CurrentValue = NULL;
		$this->Cuil_Directivo_Baja->OldValue = $this->Cuil_Directivo_Baja->CurrentValue;
		$this->Dpto_Esc_Baja->CurrentValue = NULL;
		$this->Dpto_Esc_Baja->OldValue = $this->Dpto_Esc_Baja->CurrentValue;
		$this->Localidad_Esc_Baja->CurrentValue = NULL;
		$this->Localidad_Esc_Baja->OldValue = $this->Localidad_Esc_Baja->CurrentValue;
		$this->Domicilio_Esc_Baja->CurrentValue = NULL;
		$this->Domicilio_Esc_Baja->OldValue = $this->Domicilio_Esc_Baja->CurrentValue;
		$this->Rte_Baja->CurrentValue = NULL;
		$this->Rte_Baja->OldValue = $this->Rte_Baja->CurrentValue;
		$this->Tel_Rte_Baja->CurrentValue = NULL;
		$this->Tel_Rte_Baja->OldValue = $this->Tel_Rte_Baja->CurrentValue;
		$this->Email_Rte_Baja->CurrentValue = NULL;
		$this->Email_Rte_Baja->OldValue = $this->Email_Rte_Baja->CurrentValue;
		$this->Serie_Server_Baja->CurrentValue = NULL;
		$this->Serie_Server_Baja->OldValue = $this->Serie_Server_Baja->CurrentValue;
		$this->Fecha_Pase->CurrentValue = ew_CurrentDate();
		$this->Id_Estado_Pase->CurrentValue = 2;
		$this->Ruta_Archivo->Upload->DbValue = NULL;
		$this->Ruta_Archivo->OldValue = $this->Ruta_Archivo->Upload->DbValue;
		$this->Ruta_Archivo->CurrentValue = NULL; // Clear file related field
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->Serie_Equipo->FldIsDetailKey) {
			$this->Serie_Equipo->setFormValue($objForm->GetValue("x_Serie_Equipo"));
		}
		if (!$this->Id_Hardware->FldIsDetailKey) {
			$this->Id_Hardware->setFormValue($objForm->GetValue("x_Id_Hardware"));
		}
		if (!$this->SN->FldIsDetailKey) {
			$this->SN->setFormValue($objForm->GetValue("x_SN"));
		}
		if (!$this->Modelo_Net->FldIsDetailKey) {
			$this->Modelo_Net->setFormValue($objForm->GetValue("x_Modelo_Net"));
		}
		if (!$this->Marca_Arranque->FldIsDetailKey) {
			$this->Marca_Arranque->setFormValue($objForm->GetValue("x_Marca_Arranque"));
		}
		if (!$this->Nombre_Titular->FldIsDetailKey) {
			$this->Nombre_Titular->setFormValue($objForm->GetValue("x_Nombre_Titular"));
		}
		if (!$this->Dni_Titular->FldIsDetailKey) {
			$this->Dni_Titular->setFormValue($objForm->GetValue("x_Dni_Titular"));
		}
		if (!$this->Cuil_Titular->FldIsDetailKey) {
			$this->Cuil_Titular->setFormValue($objForm->GetValue("x_Cuil_Titular"));
		}
		if (!$this->Nombre_Tutor->FldIsDetailKey) {
			$this->Nombre_Tutor->setFormValue($objForm->GetValue("x_Nombre_Tutor"));
		}
		if (!$this->DniTutor->FldIsDetailKey) {
			$this->DniTutor->setFormValue($objForm->GetValue("x_DniTutor"));
		}
		if (!$this->Domicilio->FldIsDetailKey) {
			$this->Domicilio->setFormValue($objForm->GetValue("x_Domicilio"));
		}
		if (!$this->Tel_Tutor->FldIsDetailKey) {
			$this->Tel_Tutor->setFormValue($objForm->GetValue("x_Tel_Tutor"));
		}
		if (!$this->CelTutor->FldIsDetailKey) {
			$this->CelTutor->setFormValue($objForm->GetValue("x_CelTutor"));
		}
		if (!$this->Cue_Establecimiento_Alta->FldIsDetailKey) {
			$this->Cue_Establecimiento_Alta->setFormValue($objForm->GetValue("x_Cue_Establecimiento_Alta"));
		}
		if (!$this->Escuela_Alta->FldIsDetailKey) {
			$this->Escuela_Alta->setFormValue($objForm->GetValue("x_Escuela_Alta"));
		}
		if (!$this->Directivo_Alta->FldIsDetailKey) {
			$this->Directivo_Alta->setFormValue($objForm->GetValue("x_Directivo_Alta"));
		}
		if (!$this->Cuil_Directivo_Alta->FldIsDetailKey) {
			$this->Cuil_Directivo_Alta->setFormValue($objForm->GetValue("x_Cuil_Directivo_Alta"));
		}
		if (!$this->Dpto_Esc_alta->FldIsDetailKey) {
			$this->Dpto_Esc_alta->setFormValue($objForm->GetValue("x_Dpto_Esc_alta"));
		}
		if (!$this->Localidad_Esc_Alta->FldIsDetailKey) {
			$this->Localidad_Esc_Alta->setFormValue($objForm->GetValue("x_Localidad_Esc_Alta"));
		}
		if (!$this->Domicilio_Esc_Alta->FldIsDetailKey) {
			$this->Domicilio_Esc_Alta->setFormValue($objForm->GetValue("x_Domicilio_Esc_Alta"));
		}
		if (!$this->Rte_Alta->FldIsDetailKey) {
			$this->Rte_Alta->setFormValue($objForm->GetValue("x_Rte_Alta"));
		}
		if (!$this->Tel_Rte_Alta->FldIsDetailKey) {
			$this->Tel_Rte_Alta->setFormValue($objForm->GetValue("x_Tel_Rte_Alta"));
		}
		if (!$this->Email_Rte_Alta->FldIsDetailKey) {
			$this->Email_Rte_Alta->setFormValue($objForm->GetValue("x_Email_Rte_Alta"));
		}
		if (!$this->Serie_Server_Alta->FldIsDetailKey) {
			$this->Serie_Server_Alta->setFormValue($objForm->GetValue("x_Serie_Server_Alta"));
		}
		if (!$this->Cue_Establecimiento_Baja->FldIsDetailKey) {
			$this->Cue_Establecimiento_Baja->setFormValue($objForm->GetValue("x_Cue_Establecimiento_Baja"));
		}
		if (!$this->Escuela_Baja->FldIsDetailKey) {
			$this->Escuela_Baja->setFormValue($objForm->GetValue("x_Escuela_Baja"));
		}
		if (!$this->Directivo_Baja->FldIsDetailKey) {
			$this->Directivo_Baja->setFormValue($objForm->GetValue("x_Directivo_Baja"));
		}
		if (!$this->Cuil_Directivo_Baja->FldIsDetailKey) {
			$this->Cuil_Directivo_Baja->setFormValue($objForm->GetValue("x_Cuil_Directivo_Baja"));
		}
		if (!$this->Dpto_Esc_Baja->FldIsDetailKey) {
			$this->Dpto_Esc_Baja->setFormValue($objForm->GetValue("x_Dpto_Esc_Baja"));
		}
		if (!$this->Localidad_Esc_Baja->FldIsDetailKey) {
			$this->Localidad_Esc_Baja->setFormValue($objForm->GetValue("x_Localidad_Esc_Baja"));
		}
		if (!$this->Domicilio_Esc_Baja->FldIsDetailKey) {
			$this->Domicilio_Esc_Baja->setFormValue($objForm->GetValue("x_Domicilio_Esc_Baja"));
		}
		if (!$this->Rte_Baja->FldIsDetailKey) {
			$this->Rte_Baja->setFormValue($objForm->GetValue("x_Rte_Baja"));
		}
		if (!$this->Tel_Rte_Baja->FldIsDetailKey) {
			$this->Tel_Rte_Baja->setFormValue($objForm->GetValue("x_Tel_Rte_Baja"));
		}
		if (!$this->Email_Rte_Baja->FldIsDetailKey) {
			$this->Email_Rte_Baja->setFormValue($objForm->GetValue("x_Email_Rte_Baja"));
		}
		if (!$this->Serie_Server_Baja->FldIsDetailKey) {
			$this->Serie_Server_Baja->setFormValue($objForm->GetValue("x_Serie_Server_Baja"));
		}
		if (!$this->Fecha_Pase->FldIsDetailKey) {
			$this->Fecha_Pase->setFormValue($objForm->GetValue("x_Fecha_Pase"));
			$this->Fecha_Pase->CurrentValue = ew_UnFormatDateTime($this->Fecha_Pase->CurrentValue, 7);
		}
		if (!$this->Id_Estado_Pase->FldIsDetailKey) {
			$this->Id_Estado_Pase->setFormValue($objForm->GetValue("x_Id_Estado_Pase"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->Serie_Equipo->CurrentValue = $this->Serie_Equipo->FormValue;
		$this->Id_Hardware->CurrentValue = $this->Id_Hardware->FormValue;
		$this->SN->CurrentValue = $this->SN->FormValue;
		$this->Modelo_Net->CurrentValue = $this->Modelo_Net->FormValue;
		$this->Marca_Arranque->CurrentValue = $this->Marca_Arranque->FormValue;
		$this->Nombre_Titular->CurrentValue = $this->Nombre_Titular->FormValue;
		$this->Dni_Titular->CurrentValue = $this->Dni_Titular->FormValue;
		$this->Cuil_Titular->CurrentValue = $this->Cuil_Titular->FormValue;
		$this->Nombre_Tutor->CurrentValue = $this->Nombre_Tutor->FormValue;
		$this->DniTutor->CurrentValue = $this->DniTutor->FormValue;
		$this->Domicilio->CurrentValue = $this->Domicilio->FormValue;
		$this->Tel_Tutor->CurrentValue = $this->Tel_Tutor->FormValue;
		$this->CelTutor->CurrentValue = $this->CelTutor->FormValue;
		$this->Cue_Establecimiento_Alta->CurrentValue = $this->Cue_Establecimiento_Alta->FormValue;
		$this->Escuela_Alta->CurrentValue = $this->Escuela_Alta->FormValue;
		$this->Directivo_Alta->CurrentValue = $this->Directivo_Alta->FormValue;
		$this->Cuil_Directivo_Alta->CurrentValue = $this->Cuil_Directivo_Alta->FormValue;
		$this->Dpto_Esc_alta->CurrentValue = $this->Dpto_Esc_alta->FormValue;
		$this->Localidad_Esc_Alta->CurrentValue = $this->Localidad_Esc_Alta->FormValue;
		$this->Domicilio_Esc_Alta->CurrentValue = $this->Domicilio_Esc_Alta->FormValue;
		$this->Rte_Alta->CurrentValue = $this->Rte_Alta->FormValue;
		$this->Tel_Rte_Alta->CurrentValue = $this->Tel_Rte_Alta->FormValue;
		$this->Email_Rte_Alta->CurrentValue = $this->Email_Rte_Alta->FormValue;
		$this->Serie_Server_Alta->CurrentValue = $this->Serie_Server_Alta->FormValue;
		$this->Cue_Establecimiento_Baja->CurrentValue = $this->Cue_Establecimiento_Baja->FormValue;
		$this->Escuela_Baja->CurrentValue = $this->Escuela_Baja->FormValue;
		$this->Directivo_Baja->CurrentValue = $this->Directivo_Baja->FormValue;
		$this->Cuil_Directivo_Baja->CurrentValue = $this->Cuil_Directivo_Baja->FormValue;
		$this->Dpto_Esc_Baja->CurrentValue = $this->Dpto_Esc_Baja->FormValue;
		$this->Localidad_Esc_Baja->CurrentValue = $this->Localidad_Esc_Baja->FormValue;
		$this->Domicilio_Esc_Baja->CurrentValue = $this->Domicilio_Esc_Baja->FormValue;
		$this->Rte_Baja->CurrentValue = $this->Rte_Baja->FormValue;
		$this->Tel_Rte_Baja->CurrentValue = $this->Tel_Rte_Baja->FormValue;
		$this->Email_Rte_Baja->CurrentValue = $this->Email_Rte_Baja->FormValue;
		$this->Serie_Server_Baja->CurrentValue = $this->Serie_Server_Baja->FormValue;
		$this->Fecha_Pase->CurrentValue = $this->Fecha_Pase->FormValue;
		$this->Fecha_Pase->CurrentValue = ew_UnFormatDateTime($this->Fecha_Pase->CurrentValue, 7);
		$this->Id_Estado_Pase->CurrentValue = $this->Id_Estado_Pase->FormValue;
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
		$this->Id_Pase->setDbValue($rs->fields('Id_Pase'));
		$this->Serie_Equipo->setDbValue($rs->fields('Serie_Equipo'));
		$this->Id_Hardware->setDbValue($rs->fields('Id_Hardware'));
		$this->SN->setDbValue($rs->fields('SN'));
		$this->Modelo_Net->setDbValue($rs->fields('Modelo_Net'));
		$this->Marca_Arranque->setDbValue($rs->fields('Marca_Arranque'));
		$this->Nombre_Titular->setDbValue($rs->fields('Nombre_Titular'));
		$this->Dni_Titular->setDbValue($rs->fields('Dni_Titular'));
		$this->Cuil_Titular->setDbValue($rs->fields('Cuil_Titular'));
		$this->Nombre_Tutor->setDbValue($rs->fields('Nombre_Tutor'));
		$this->DniTutor->setDbValue($rs->fields('DniTutor'));
		$this->Domicilio->setDbValue($rs->fields('Domicilio'));
		$this->Tel_Tutor->setDbValue($rs->fields('Tel_Tutor'));
		$this->CelTutor->setDbValue($rs->fields('CelTutor'));
		$this->Cue_Establecimiento_Alta->setDbValue($rs->fields('Cue_Establecimiento_Alta'));
		$this->Escuela_Alta->setDbValue($rs->fields('Escuela_Alta'));
		$this->Directivo_Alta->setDbValue($rs->fields('Directivo_Alta'));
		$this->Cuil_Directivo_Alta->setDbValue($rs->fields('Cuil_Directivo_Alta'));
		$this->Dpto_Esc_alta->setDbValue($rs->fields('Dpto_Esc_alta'));
		$this->Localidad_Esc_Alta->setDbValue($rs->fields('Localidad_Esc_Alta'));
		$this->Domicilio_Esc_Alta->setDbValue($rs->fields('Domicilio_Esc_Alta'));
		$this->Rte_Alta->setDbValue($rs->fields('Rte_Alta'));
		$this->Tel_Rte_Alta->setDbValue($rs->fields('Tel_Rte_Alta'));
		$this->Email_Rte_Alta->setDbValue($rs->fields('Email_Rte_Alta'));
		$this->Serie_Server_Alta->setDbValue($rs->fields('Serie_Server_Alta'));
		$this->Cue_Establecimiento_Baja->setDbValue($rs->fields('Cue_Establecimiento_Baja'));
		$this->Escuela_Baja->setDbValue($rs->fields('Escuela_Baja'));
		$this->Directivo_Baja->setDbValue($rs->fields('Directivo_Baja'));
		$this->Cuil_Directivo_Baja->setDbValue($rs->fields('Cuil_Directivo_Baja'));
		$this->Dpto_Esc_Baja->setDbValue($rs->fields('Dpto_Esc_Baja'));
		$this->Localidad_Esc_Baja->setDbValue($rs->fields('Localidad_Esc_Baja'));
		$this->Domicilio_Esc_Baja->setDbValue($rs->fields('Domicilio_Esc_Baja'));
		$this->Rte_Baja->setDbValue($rs->fields('Rte_Baja'));
		$this->Tel_Rte_Baja->setDbValue($rs->fields('Tel_Rte_Baja'));
		$this->Email_Rte_Baja->setDbValue($rs->fields('Email_Rte_Baja'));
		$this->Serie_Server_Baja->setDbValue($rs->fields('Serie_Server_Baja'));
		$this->Fecha_Pase->setDbValue($rs->fields('Fecha_Pase'));
		$this->Id_Estado_Pase->setDbValue($rs->fields('Id_Estado_Pase'));
		$this->Ruta_Archivo->Upload->DbValue = $rs->fields('Ruta_Archivo');
		$this->Ruta_Archivo->CurrentValue = $this->Ruta_Archivo->Upload->DbValue;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Id_Pase->DbValue = $row['Id_Pase'];
		$this->Serie_Equipo->DbValue = $row['Serie_Equipo'];
		$this->Id_Hardware->DbValue = $row['Id_Hardware'];
		$this->SN->DbValue = $row['SN'];
		$this->Modelo_Net->DbValue = $row['Modelo_Net'];
		$this->Marca_Arranque->DbValue = $row['Marca_Arranque'];
		$this->Nombre_Titular->DbValue = $row['Nombre_Titular'];
		$this->Dni_Titular->DbValue = $row['Dni_Titular'];
		$this->Cuil_Titular->DbValue = $row['Cuil_Titular'];
		$this->Nombre_Tutor->DbValue = $row['Nombre_Tutor'];
		$this->DniTutor->DbValue = $row['DniTutor'];
		$this->Domicilio->DbValue = $row['Domicilio'];
		$this->Tel_Tutor->DbValue = $row['Tel_Tutor'];
		$this->CelTutor->DbValue = $row['CelTutor'];
		$this->Cue_Establecimiento_Alta->DbValue = $row['Cue_Establecimiento_Alta'];
		$this->Escuela_Alta->DbValue = $row['Escuela_Alta'];
		$this->Directivo_Alta->DbValue = $row['Directivo_Alta'];
		$this->Cuil_Directivo_Alta->DbValue = $row['Cuil_Directivo_Alta'];
		$this->Dpto_Esc_alta->DbValue = $row['Dpto_Esc_alta'];
		$this->Localidad_Esc_Alta->DbValue = $row['Localidad_Esc_Alta'];
		$this->Domicilio_Esc_Alta->DbValue = $row['Domicilio_Esc_Alta'];
		$this->Rte_Alta->DbValue = $row['Rte_Alta'];
		$this->Tel_Rte_Alta->DbValue = $row['Tel_Rte_Alta'];
		$this->Email_Rte_Alta->DbValue = $row['Email_Rte_Alta'];
		$this->Serie_Server_Alta->DbValue = $row['Serie_Server_Alta'];
		$this->Cue_Establecimiento_Baja->DbValue = $row['Cue_Establecimiento_Baja'];
		$this->Escuela_Baja->DbValue = $row['Escuela_Baja'];
		$this->Directivo_Baja->DbValue = $row['Directivo_Baja'];
		$this->Cuil_Directivo_Baja->DbValue = $row['Cuil_Directivo_Baja'];
		$this->Dpto_Esc_Baja->DbValue = $row['Dpto_Esc_Baja'];
		$this->Localidad_Esc_Baja->DbValue = $row['Localidad_Esc_Baja'];
		$this->Domicilio_Esc_Baja->DbValue = $row['Domicilio_Esc_Baja'];
		$this->Rte_Baja->DbValue = $row['Rte_Baja'];
		$this->Tel_Rte_Baja->DbValue = $row['Tel_Rte_Baja'];
		$this->Email_Rte_Baja->DbValue = $row['Email_Rte_Baja'];
		$this->Serie_Server_Baja->DbValue = $row['Serie_Server_Baja'];
		$this->Fecha_Pase->DbValue = $row['Fecha_Pase'];
		$this->Id_Estado_Pase->DbValue = $row['Id_Estado_Pase'];
		$this->Ruta_Archivo->Upload->DbValue = $row['Ruta_Archivo'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("Id_Pase")) <> "")
			$this->Id_Pase->CurrentValue = $this->getKey("Id_Pase"); // Id_Pase
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// Id_Pase
		// Serie_Equipo
		// Id_Hardware
		// SN
		// Modelo_Net
		// Marca_Arranque
		// Nombre_Titular
		// Dni_Titular
		// Cuil_Titular
		// Nombre_Tutor
		// DniTutor
		// Domicilio
		// Tel_Tutor
		// CelTutor
		// Cue_Establecimiento_Alta
		// Escuela_Alta
		// Directivo_Alta
		// Cuil_Directivo_Alta
		// Dpto_Esc_alta
		// Localidad_Esc_Alta
		// Domicilio_Esc_Alta
		// Rte_Alta
		// Tel_Rte_Alta
		// Email_Rte_Alta
		// Serie_Server_Alta
		// Cue_Establecimiento_Baja
		// Escuela_Baja
		// Directivo_Baja
		// Cuil_Directivo_Baja
		// Dpto_Esc_Baja
		// Localidad_Esc_Baja
		// Domicilio_Esc_Baja
		// Rte_Baja
		// Tel_Rte_Baja
		// Email_Rte_Baja
		// Serie_Server_Baja
		// Fecha_Pase
		// Id_Estado_Pase
		// Ruta_Archivo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// Serie_Equipo
		$this->Serie_Equipo->ViewValue = $this->Serie_Equipo->CurrentValue;
		if (strval($this->Serie_Equipo->CurrentValue) <> "") {
			$sFilterWrk = "`NroSerie`" . ew_SearchString("=", $this->Serie_Equipo->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `NroSerie`, `NroSerie` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `equipos`";
		$sWhereWrk = "";
		$this->Serie_Equipo->LookupFilters = array("dx1" => "`NroSerie`");
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Serie_Equipo, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Serie_Equipo->ViewValue = $this->Serie_Equipo->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Serie_Equipo->ViewValue = $this->Serie_Equipo->CurrentValue;
			}
		} else {
			$this->Serie_Equipo->ViewValue = NULL;
		}
		$this->Serie_Equipo->ViewCustomAttributes = "";

		// Id_Hardware
		$this->Id_Hardware->ViewValue = $this->Id_Hardware->CurrentValue;
		$this->Id_Hardware->ViewCustomAttributes = "";

		// SN
		$this->SN->ViewValue = $this->SN->CurrentValue;
		$this->SN->ViewCustomAttributes = "";

		// Modelo_Net
		if (strval($this->Modelo_Net->CurrentValue) <> "") {
			$sFilterWrk = "`Descripcion`" . ew_SearchString("=", $this->Modelo_Net->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `Descripcion`, `Descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `modelo`";
		$sWhereWrk = "";
		$this->Modelo_Net->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Modelo_Net, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Modelo_Net->ViewValue = $this->Modelo_Net->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Modelo_Net->ViewValue = $this->Modelo_Net->CurrentValue;
			}
		} else {
			$this->Modelo_Net->ViewValue = NULL;
		}
		$this->Modelo_Net->ViewCustomAttributes = "";

		// Marca_Arranque
		$this->Marca_Arranque->ViewValue = $this->Marca_Arranque->CurrentValue;
		$this->Marca_Arranque->ViewCustomAttributes = "";

		// Nombre_Titular
		$this->Nombre_Titular->ViewValue = $this->Nombre_Titular->CurrentValue;
		if (strval($this->Nombre_Titular->CurrentValue) <> "") {
			$sFilterWrk = "`Apellidos_Nombres`" . ew_SearchString("=", $this->Nombre_Titular->CurrentValue, EW_DATATYPE_MEMO, "");
		$sSqlWrk = "SELECT `Apellidos_Nombres`, `Apellidos_Nombres` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `personas`";
		$sWhereWrk = "";
		$this->Nombre_Titular->LookupFilters = array("dx1" => "`Apellidos_Nombres`");
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Nombre_Titular, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Nombre_Titular->ViewValue = $this->Nombre_Titular->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Nombre_Titular->ViewValue = $this->Nombre_Titular->CurrentValue;
			}
		} else {
			$this->Nombre_Titular->ViewValue = NULL;
		}
		$this->Nombre_Titular->ViewCustomAttributes = "";

		// Dni_Titular
		$this->Dni_Titular->ViewValue = $this->Dni_Titular->CurrentValue;
		$this->Dni_Titular->ViewCustomAttributes = "";

		// Cuil_Titular
		$this->Cuil_Titular->ViewValue = $this->Cuil_Titular->CurrentValue;
		$this->Cuil_Titular->ViewCustomAttributes = "";

		// Nombre_Tutor
		$this->Nombre_Tutor->ViewValue = $this->Nombre_Tutor->CurrentValue;
		if (strval($this->Nombre_Tutor->CurrentValue) <> "") {
			$sFilterWrk = "`Apellidos_Nombres`" . ew_SearchString("=", $this->Nombre_Tutor->CurrentValue, EW_DATATYPE_MEMO, "");
		$sSqlWrk = "SELECT `Apellidos_Nombres`, `Apellidos_Nombres` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tutores`";
		$sWhereWrk = "";
		$this->Nombre_Tutor->LookupFilters = array("dx1" => "`Apellidos_Nombres`");
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Nombre_Tutor, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Nombre_Tutor->ViewValue = $this->Nombre_Tutor->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Nombre_Tutor->ViewValue = $this->Nombre_Tutor->CurrentValue;
			}
		} else {
			$this->Nombre_Tutor->ViewValue = NULL;
		}
		$this->Nombre_Tutor->ViewCustomAttributes = "";

		// DniTutor
		$this->DniTutor->ViewValue = $this->DniTutor->CurrentValue;
		$this->DniTutor->ViewCustomAttributes = "";

		// Domicilio
		$this->Domicilio->ViewValue = $this->Domicilio->CurrentValue;
		$this->Domicilio->ViewCustomAttributes = "";

		// Tel_Tutor
		$this->Tel_Tutor->ViewValue = $this->Tel_Tutor->CurrentValue;
		$this->Tel_Tutor->ViewCustomAttributes = "";

		// CelTutor
		$this->CelTutor->ViewValue = $this->CelTutor->CurrentValue;
		$this->CelTutor->ViewCustomAttributes = "";

		// Cue_Establecimiento_Alta
		$this->Cue_Establecimiento_Alta->ViewValue = $this->Cue_Establecimiento_Alta->CurrentValue;
		if (strval($this->Cue_Establecimiento_Alta->CurrentValue) <> "") {
			$sFilterWrk = "`Cue_Establecimiento`" . ew_SearchString("=", $this->Cue_Establecimiento_Alta->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `Cue_Establecimiento`, `Cue_Establecimiento` AS `DispFld`, `Nombre_Establecimiento` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `establecimientos_educativos_pase`";
		$sWhereWrk = "";
		$this->Cue_Establecimiento_Alta->LookupFilters = array("dx1" => "`Cue_Establecimiento`", "dx2" => "`Nombre_Establecimiento`");
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Cue_Establecimiento_Alta, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->Cue_Establecimiento_Alta->ViewValue = $this->Cue_Establecimiento_Alta->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Cue_Establecimiento_Alta->ViewValue = $this->Cue_Establecimiento_Alta->CurrentValue;
			}
		} else {
			$this->Cue_Establecimiento_Alta->ViewValue = NULL;
		}
		$this->Cue_Establecimiento_Alta->ViewCustomAttributes = "";

		// Escuela_Alta
		$this->Escuela_Alta->ViewValue = $this->Escuela_Alta->CurrentValue;
		$this->Escuela_Alta->ViewCustomAttributes = "";

		// Directivo_Alta
		$this->Directivo_Alta->ViewValue = $this->Directivo_Alta->CurrentValue;
		$this->Directivo_Alta->ViewCustomAttributes = "";

		// Cuil_Directivo_Alta
		$this->Cuil_Directivo_Alta->ViewValue = $this->Cuil_Directivo_Alta->CurrentValue;
		$this->Cuil_Directivo_Alta->ViewCustomAttributes = "";

		// Dpto_Esc_alta
		if (strval($this->Dpto_Esc_alta->CurrentValue) <> "") {
			$sFilterWrk = "`Nombre`" . ew_SearchString("=", $this->Dpto_Esc_alta->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `Nombre`, `Nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departamento`";
		$sWhereWrk = "";
		$this->Dpto_Esc_alta->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Dpto_Esc_alta, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Dpto_Esc_alta->ViewValue = $this->Dpto_Esc_alta->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Dpto_Esc_alta->ViewValue = $this->Dpto_Esc_alta->CurrentValue;
			}
		} else {
			$this->Dpto_Esc_alta->ViewValue = NULL;
		}
		$this->Dpto_Esc_alta->ViewCustomAttributes = "";

		// Localidad_Esc_Alta
		if (strval($this->Localidad_Esc_Alta->CurrentValue) <> "") {
			$sFilterWrk = "`Nombre`" . ew_SearchString("=", $this->Localidad_Esc_Alta->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `Nombre`, `Nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `localidades`";
		$sWhereWrk = "";
		$this->Localidad_Esc_Alta->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Localidad_Esc_Alta, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Localidad_Esc_Alta->ViewValue = $this->Localidad_Esc_Alta->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Localidad_Esc_Alta->ViewValue = $this->Localidad_Esc_Alta->CurrentValue;
			}
		} else {
			$this->Localidad_Esc_Alta->ViewValue = NULL;
		}
		$this->Localidad_Esc_Alta->ViewCustomAttributes = "";

		// Domicilio_Esc_Alta
		$this->Domicilio_Esc_Alta->ViewValue = $this->Domicilio_Esc_Alta->CurrentValue;
		$this->Domicilio_Esc_Alta->ViewCustomAttributes = "";

		// Rte_Alta
		$this->Rte_Alta->ViewValue = $this->Rte_Alta->CurrentValue;
		$this->Rte_Alta->ViewCustomAttributes = "";

		// Tel_Rte_Alta
		$this->Tel_Rte_Alta->ViewValue = $this->Tel_Rte_Alta->CurrentValue;
		$this->Tel_Rte_Alta->ViewCustomAttributes = "";

		// Email_Rte_Alta
		$this->Email_Rte_Alta->ViewValue = $this->Email_Rte_Alta->CurrentValue;
		$this->Email_Rte_Alta->ViewCustomAttributes = "";

		// Serie_Server_Alta
		$this->Serie_Server_Alta->ViewValue = $this->Serie_Server_Alta->CurrentValue;
		$this->Serie_Server_Alta->ViewCustomAttributes = "";

		// Cue_Establecimiento_Baja
		$this->Cue_Establecimiento_Baja->ViewValue = $this->Cue_Establecimiento_Baja->CurrentValue;
		if (strval($this->Cue_Establecimiento_Baja->CurrentValue) <> "") {
			$sFilterWrk = "`Cue_Establecimiento`" . ew_SearchString("=", $this->Cue_Establecimiento_Baja->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `Cue_Establecimiento`, `Cue_Establecimiento` AS `DispFld`, `Nombre_Establecimiento` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `establecimientos_educativos_pase`";
		$sWhereWrk = "";
		$this->Cue_Establecimiento_Baja->LookupFilters = array("dx1" => "`Cue_Establecimiento`", "dx2" => "`Nombre_Establecimiento`");
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Cue_Establecimiento_Baja, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->Cue_Establecimiento_Baja->ViewValue = $this->Cue_Establecimiento_Baja->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Cue_Establecimiento_Baja->ViewValue = $this->Cue_Establecimiento_Baja->CurrentValue;
			}
		} else {
			$this->Cue_Establecimiento_Baja->ViewValue = NULL;
		}
		$this->Cue_Establecimiento_Baja->ViewCustomAttributes = "";

		// Escuela_Baja
		$this->Escuela_Baja->ViewValue = $this->Escuela_Baja->CurrentValue;
		$this->Escuela_Baja->ViewCustomAttributes = "";

		// Directivo_Baja
		$this->Directivo_Baja->ViewValue = $this->Directivo_Baja->CurrentValue;
		$this->Directivo_Baja->ViewCustomAttributes = "";

		// Cuil_Directivo_Baja
		$this->Cuil_Directivo_Baja->ViewValue = $this->Cuil_Directivo_Baja->CurrentValue;
		$this->Cuil_Directivo_Baja->ViewCustomAttributes = "";

		// Dpto_Esc_Baja
		if (strval($this->Dpto_Esc_Baja->CurrentValue) <> "") {
			$sFilterWrk = "`Nombre`" . ew_SearchString("=", $this->Dpto_Esc_Baja->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `Nombre`, `Nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departamento`";
		$sWhereWrk = "";
		$this->Dpto_Esc_Baja->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Dpto_Esc_Baja, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Dpto_Esc_Baja->ViewValue = $this->Dpto_Esc_Baja->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Dpto_Esc_Baja->ViewValue = $this->Dpto_Esc_Baja->CurrentValue;
			}
		} else {
			$this->Dpto_Esc_Baja->ViewValue = NULL;
		}
		$this->Dpto_Esc_Baja->ViewCustomAttributes = "";

		// Localidad_Esc_Baja
		if (strval($this->Localidad_Esc_Baja->CurrentValue) <> "") {
			$sFilterWrk = "`Nombre`" . ew_SearchString("=", $this->Localidad_Esc_Baja->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `Nombre`, `Nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `localidades`";
		$sWhereWrk = "";
		$this->Localidad_Esc_Baja->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Localidad_Esc_Baja, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Localidad_Esc_Baja->ViewValue = $this->Localidad_Esc_Baja->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Localidad_Esc_Baja->ViewValue = $this->Localidad_Esc_Baja->CurrentValue;
			}
		} else {
			$this->Localidad_Esc_Baja->ViewValue = NULL;
		}
		$this->Localidad_Esc_Baja->ViewCustomAttributes = "";

		// Domicilio_Esc_Baja
		$this->Domicilio_Esc_Baja->ViewValue = $this->Domicilio_Esc_Baja->CurrentValue;
		$this->Domicilio_Esc_Baja->ViewCustomAttributes = "";

		// Rte_Baja
		$this->Rte_Baja->ViewValue = $this->Rte_Baja->CurrentValue;
		$this->Rte_Baja->ViewCustomAttributes = "";

		// Tel_Rte_Baja
		$this->Tel_Rte_Baja->ViewValue = $this->Tel_Rte_Baja->CurrentValue;
		$this->Tel_Rte_Baja->ViewCustomAttributes = "";

		// Email_Rte_Baja
		$this->Email_Rte_Baja->ViewValue = $this->Email_Rte_Baja->CurrentValue;
		$this->Email_Rte_Baja->ViewCustomAttributes = "";

		// Serie_Server_Baja
		$this->Serie_Server_Baja->ViewValue = $this->Serie_Server_Baja->CurrentValue;
		$this->Serie_Server_Baja->ViewCustomAttributes = "";

		// Fecha_Pase
		$this->Fecha_Pase->ViewValue = $this->Fecha_Pase->CurrentValue;
		$this->Fecha_Pase->ViewValue = ew_FormatDateTime($this->Fecha_Pase->ViewValue, 7);
		$this->Fecha_Pase->ViewCustomAttributes = "";

		// Id_Estado_Pase
		if (strval($this->Id_Estado_Pase->CurrentValue) <> "") {
			$sFilterWrk = "`Id_Estado_Pase`" . ew_SearchString("=", $this->Id_Estado_Pase->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `Id_Estado_Pase`, `Descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `estado_pase`";
		$sWhereWrk = "";
		$this->Id_Estado_Pase->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Id_Estado_Pase, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Id_Estado_Pase->ViewValue = $this->Id_Estado_Pase->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Id_Estado_Pase->ViewValue = $this->Id_Estado_Pase->CurrentValue;
			}
		} else {
			$this->Id_Estado_Pase->ViewValue = NULL;
		}
		$this->Id_Estado_Pase->ViewCustomAttributes = "";

		// Ruta_Archivo
		$this->Ruta_Archivo->UploadPath = 'ArchivosPase';
		if (!ew_Empty($this->Ruta_Archivo->Upload->DbValue)) {
			$this->Ruta_Archivo->ViewValue = $this->Ruta_Archivo->Upload->DbValue;
		} else {
			$this->Ruta_Archivo->ViewValue = "";
		}
		$this->Ruta_Archivo->ViewCustomAttributes = "";

			// Serie_Equipo
			$this->Serie_Equipo->LinkCustomAttributes = "";
			$this->Serie_Equipo->HrefValue = "";
			$this->Serie_Equipo->TooltipValue = "";

			// Id_Hardware
			$this->Id_Hardware->LinkCustomAttributes = "";
			$this->Id_Hardware->HrefValue = "";
			$this->Id_Hardware->TooltipValue = "";

			// SN
			$this->SN->LinkCustomAttributes = "";
			$this->SN->HrefValue = "";
			$this->SN->TooltipValue = "";

			// Modelo_Net
			$this->Modelo_Net->LinkCustomAttributes = "";
			$this->Modelo_Net->HrefValue = "";
			$this->Modelo_Net->TooltipValue = "";

			// Marca_Arranque
			$this->Marca_Arranque->LinkCustomAttributes = "";
			$this->Marca_Arranque->HrefValue = "";
			$this->Marca_Arranque->TooltipValue = "";

			// Nombre_Titular
			$this->Nombre_Titular->LinkCustomAttributes = "";
			$this->Nombre_Titular->HrefValue = "";
			$this->Nombre_Titular->TooltipValue = "";

			// Dni_Titular
			$this->Dni_Titular->LinkCustomAttributes = "";
			$this->Dni_Titular->HrefValue = "";
			$this->Dni_Titular->TooltipValue = "";

			// Cuil_Titular
			$this->Cuil_Titular->LinkCustomAttributes = "";
			$this->Cuil_Titular->HrefValue = "";
			$this->Cuil_Titular->TooltipValue = "";

			// Nombre_Tutor
			$this->Nombre_Tutor->LinkCustomAttributes = "";
			$this->Nombre_Tutor->HrefValue = "";
			$this->Nombre_Tutor->TooltipValue = "";

			// DniTutor
			$this->DniTutor->LinkCustomAttributes = "";
			$this->DniTutor->HrefValue = "";
			$this->DniTutor->TooltipValue = "";

			// Domicilio
			$this->Domicilio->LinkCustomAttributes = "";
			$this->Domicilio->HrefValue = "";
			$this->Domicilio->TooltipValue = "";

			// Tel_Tutor
			$this->Tel_Tutor->LinkCustomAttributes = "";
			$this->Tel_Tutor->HrefValue = "";
			$this->Tel_Tutor->TooltipValue = "";

			// CelTutor
			$this->CelTutor->LinkCustomAttributes = "";
			$this->CelTutor->HrefValue = "";
			$this->CelTutor->TooltipValue = "";

			// Cue_Establecimiento_Alta
			$this->Cue_Establecimiento_Alta->LinkCustomAttributes = "";
			$this->Cue_Establecimiento_Alta->HrefValue = "";
			$this->Cue_Establecimiento_Alta->TooltipValue = "";

			// Escuela_Alta
			$this->Escuela_Alta->LinkCustomAttributes = "";
			$this->Escuela_Alta->HrefValue = "";
			$this->Escuela_Alta->TooltipValue = "";

			// Directivo_Alta
			$this->Directivo_Alta->LinkCustomAttributes = "";
			$this->Directivo_Alta->HrefValue = "";
			$this->Directivo_Alta->TooltipValue = "";

			// Cuil_Directivo_Alta
			$this->Cuil_Directivo_Alta->LinkCustomAttributes = "";
			$this->Cuil_Directivo_Alta->HrefValue = "";
			$this->Cuil_Directivo_Alta->TooltipValue = "";

			// Dpto_Esc_alta
			$this->Dpto_Esc_alta->LinkCustomAttributes = "";
			$this->Dpto_Esc_alta->HrefValue = "";
			$this->Dpto_Esc_alta->TooltipValue = "";

			// Localidad_Esc_Alta
			$this->Localidad_Esc_Alta->LinkCustomAttributes = "";
			$this->Localidad_Esc_Alta->HrefValue = "";
			$this->Localidad_Esc_Alta->TooltipValue = "";

			// Domicilio_Esc_Alta
			$this->Domicilio_Esc_Alta->LinkCustomAttributes = "";
			$this->Domicilio_Esc_Alta->HrefValue = "";
			$this->Domicilio_Esc_Alta->TooltipValue = "";

			// Rte_Alta
			$this->Rte_Alta->LinkCustomAttributes = "";
			$this->Rte_Alta->HrefValue = "";
			$this->Rte_Alta->TooltipValue = "";

			// Tel_Rte_Alta
			$this->Tel_Rte_Alta->LinkCustomAttributes = "";
			$this->Tel_Rte_Alta->HrefValue = "";
			$this->Tel_Rte_Alta->TooltipValue = "";

			// Email_Rte_Alta
			$this->Email_Rte_Alta->LinkCustomAttributes = "";
			$this->Email_Rte_Alta->HrefValue = "";
			$this->Email_Rte_Alta->TooltipValue = "";

			// Serie_Server_Alta
			$this->Serie_Server_Alta->LinkCustomAttributes = "";
			$this->Serie_Server_Alta->HrefValue = "";
			$this->Serie_Server_Alta->TooltipValue = "";

			// Cue_Establecimiento_Baja
			$this->Cue_Establecimiento_Baja->LinkCustomAttributes = "";
			$this->Cue_Establecimiento_Baja->HrefValue = "";
			$this->Cue_Establecimiento_Baja->TooltipValue = "";

			// Escuela_Baja
			$this->Escuela_Baja->LinkCustomAttributes = "";
			$this->Escuela_Baja->HrefValue = "";
			$this->Escuela_Baja->TooltipValue = "";

			// Directivo_Baja
			$this->Directivo_Baja->LinkCustomAttributes = "";
			$this->Directivo_Baja->HrefValue = "";
			$this->Directivo_Baja->TooltipValue = "";

			// Cuil_Directivo_Baja
			$this->Cuil_Directivo_Baja->LinkCustomAttributes = "";
			$this->Cuil_Directivo_Baja->HrefValue = "";
			$this->Cuil_Directivo_Baja->TooltipValue = "";

			// Dpto_Esc_Baja
			$this->Dpto_Esc_Baja->LinkCustomAttributes = "";
			$this->Dpto_Esc_Baja->HrefValue = "";
			$this->Dpto_Esc_Baja->TooltipValue = "";

			// Localidad_Esc_Baja
			$this->Localidad_Esc_Baja->LinkCustomAttributes = "";
			$this->Localidad_Esc_Baja->HrefValue = "";
			$this->Localidad_Esc_Baja->TooltipValue = "";

			// Domicilio_Esc_Baja
			$this->Domicilio_Esc_Baja->LinkCustomAttributes = "";
			$this->Domicilio_Esc_Baja->HrefValue = "";
			$this->Domicilio_Esc_Baja->TooltipValue = "";

			// Rte_Baja
			$this->Rte_Baja->LinkCustomAttributes = "";
			$this->Rte_Baja->HrefValue = "";
			$this->Rte_Baja->TooltipValue = "";

			// Tel_Rte_Baja
			$this->Tel_Rte_Baja->LinkCustomAttributes = "";
			$this->Tel_Rte_Baja->HrefValue = "";
			$this->Tel_Rte_Baja->TooltipValue = "";

			// Email_Rte_Baja
			$this->Email_Rte_Baja->LinkCustomAttributes = "";
			$this->Email_Rte_Baja->HrefValue = "";
			$this->Email_Rte_Baja->TooltipValue = "";

			// Serie_Server_Baja
			$this->Serie_Server_Baja->LinkCustomAttributes = "";
			$this->Serie_Server_Baja->HrefValue = "";
			$this->Serie_Server_Baja->TooltipValue = "";

			// Fecha_Pase
			$this->Fecha_Pase->LinkCustomAttributes = "";
			$this->Fecha_Pase->HrefValue = "";
			$this->Fecha_Pase->TooltipValue = "";

			// Id_Estado_Pase
			$this->Id_Estado_Pase->LinkCustomAttributes = "";
			$this->Id_Estado_Pase->HrefValue = "";
			$this->Id_Estado_Pase->TooltipValue = "";

			// Ruta_Archivo
			$this->Ruta_Archivo->LinkCustomAttributes = "";
			$this->Ruta_Archivo->HrefValue = "";
			$this->Ruta_Archivo->HrefValue2 = $this->Ruta_Archivo->UploadPath . $this->Ruta_Archivo->Upload->DbValue;
			$this->Ruta_Archivo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// Serie_Equipo
			$this->Serie_Equipo->EditAttrs["class"] = "form-control";
			$this->Serie_Equipo->EditCustomAttributes = "";
			$this->Serie_Equipo->EditValue = ew_HtmlEncode($this->Serie_Equipo->CurrentValue);
			if (strval($this->Serie_Equipo->CurrentValue) <> "") {
				$sFilterWrk = "`NroSerie`" . ew_SearchString("=", $this->Serie_Equipo->CurrentValue, EW_DATATYPE_STRING, "");
			$sSqlWrk = "SELECT `NroSerie`, `NroSerie` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `equipos`";
			$sWhereWrk = "";
			$this->Serie_Equipo->LookupFilters = array("dx1" => "`NroSerie`");
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Serie_Equipo, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->Serie_Equipo->EditValue = $this->Serie_Equipo->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->Serie_Equipo->EditValue = ew_HtmlEncode($this->Serie_Equipo->CurrentValue);
				}
			} else {
				$this->Serie_Equipo->EditValue = NULL;
			}
			$this->Serie_Equipo->PlaceHolder = ew_RemoveHtml($this->Serie_Equipo->FldCaption());

			// Id_Hardware
			$this->Id_Hardware->EditAttrs["class"] = "form-control";
			$this->Id_Hardware->EditCustomAttributes = "";
			$this->Id_Hardware->EditValue = ew_HtmlEncode($this->Id_Hardware->CurrentValue);
			$this->Id_Hardware->PlaceHolder = ew_RemoveHtml($this->Id_Hardware->FldCaption());

			// SN
			$this->SN->EditAttrs["class"] = "form-control";
			$this->SN->EditCustomAttributes = "";
			$this->SN->EditValue = ew_HtmlEncode($this->SN->CurrentValue);
			$this->SN->PlaceHolder = ew_RemoveHtml($this->SN->FldCaption());

			// Modelo_Net
			$this->Modelo_Net->EditAttrs["class"] = "form-control";
			$this->Modelo_Net->EditCustomAttributes = "";
			if (trim(strval($this->Modelo_Net->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Descripcion`" . ew_SearchString("=", $this->Modelo_Net->CurrentValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT `Descripcion`, `Descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `modelo`";
			$sWhereWrk = "";
			$this->Modelo_Net->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Modelo_Net, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->Modelo_Net->EditValue = $arwrk;

			// Marca_Arranque
			$this->Marca_Arranque->EditAttrs["class"] = "form-control";
			$this->Marca_Arranque->EditCustomAttributes = "";
			$this->Marca_Arranque->EditValue = ew_HtmlEncode($this->Marca_Arranque->CurrentValue);
			$this->Marca_Arranque->PlaceHolder = ew_RemoveHtml($this->Marca_Arranque->FldCaption());

			// Nombre_Titular
			$this->Nombre_Titular->EditAttrs["class"] = "form-control";
			$this->Nombre_Titular->EditCustomAttributes = "";
			$this->Nombre_Titular->EditValue = ew_HtmlEncode($this->Nombre_Titular->CurrentValue);
			if (strval($this->Nombre_Titular->CurrentValue) <> "") {
				$sFilterWrk = "`Apellidos_Nombres`" . ew_SearchString("=", $this->Nombre_Titular->CurrentValue, EW_DATATYPE_MEMO, "");
			$sSqlWrk = "SELECT `Apellidos_Nombres`, `Apellidos_Nombres` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `personas`";
			$sWhereWrk = "";
			$this->Nombre_Titular->LookupFilters = array("dx1" => "`Apellidos_Nombres`");
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Nombre_Titular, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->Nombre_Titular->EditValue = $this->Nombre_Titular->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->Nombre_Titular->EditValue = ew_HtmlEncode($this->Nombre_Titular->CurrentValue);
				}
			} else {
				$this->Nombre_Titular->EditValue = NULL;
			}
			$this->Nombre_Titular->PlaceHolder = ew_RemoveHtml($this->Nombre_Titular->FldCaption());

			// Dni_Titular
			$this->Dni_Titular->EditAttrs["class"] = "form-control";
			$this->Dni_Titular->EditCustomAttributes = "";
			$this->Dni_Titular->EditValue = ew_HtmlEncode($this->Dni_Titular->CurrentValue);
			$this->Dni_Titular->PlaceHolder = ew_RemoveHtml($this->Dni_Titular->FldCaption());

			// Cuil_Titular
			$this->Cuil_Titular->EditAttrs["class"] = "form-control";
			$this->Cuil_Titular->EditCustomAttributes = "";
			$this->Cuil_Titular->EditValue = ew_HtmlEncode($this->Cuil_Titular->CurrentValue);
			$this->Cuil_Titular->PlaceHolder = ew_RemoveHtml($this->Cuil_Titular->FldCaption());

			// Nombre_Tutor
			$this->Nombre_Tutor->EditAttrs["class"] = "form-control";
			$this->Nombre_Tutor->EditCustomAttributes = "";
			$this->Nombre_Tutor->EditValue = ew_HtmlEncode($this->Nombre_Tutor->CurrentValue);
			if (strval($this->Nombre_Tutor->CurrentValue) <> "") {
				$sFilterWrk = "`Apellidos_Nombres`" . ew_SearchString("=", $this->Nombre_Tutor->CurrentValue, EW_DATATYPE_MEMO, "");
			$sSqlWrk = "SELECT `Apellidos_Nombres`, `Apellidos_Nombres` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tutores`";
			$sWhereWrk = "";
			$this->Nombre_Tutor->LookupFilters = array("dx1" => "`Apellidos_Nombres`");
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Nombre_Tutor, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->Nombre_Tutor->EditValue = $this->Nombre_Tutor->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->Nombre_Tutor->EditValue = ew_HtmlEncode($this->Nombre_Tutor->CurrentValue);
				}
			} else {
				$this->Nombre_Tutor->EditValue = NULL;
			}
			$this->Nombre_Tutor->PlaceHolder = ew_RemoveHtml($this->Nombre_Tutor->FldCaption());

			// DniTutor
			$this->DniTutor->EditAttrs["class"] = "form-control";
			$this->DniTutor->EditCustomAttributes = "";
			$this->DniTutor->EditValue = ew_HtmlEncode($this->DniTutor->CurrentValue);
			$this->DniTutor->PlaceHolder = ew_RemoveHtml($this->DniTutor->FldCaption());

			// Domicilio
			$this->Domicilio->EditAttrs["class"] = "form-control";
			$this->Domicilio->EditCustomAttributes = "";
			$this->Domicilio->EditValue = ew_HtmlEncode($this->Domicilio->CurrentValue);
			$this->Domicilio->PlaceHolder = ew_RemoveHtml($this->Domicilio->FldCaption());

			// Tel_Tutor
			$this->Tel_Tutor->EditAttrs["class"] = "form-control";
			$this->Tel_Tutor->EditCustomAttributes = "";
			$this->Tel_Tutor->EditValue = ew_HtmlEncode($this->Tel_Tutor->CurrentValue);
			$this->Tel_Tutor->PlaceHolder = ew_RemoveHtml($this->Tel_Tutor->FldCaption());

			// CelTutor
			$this->CelTutor->EditAttrs["class"] = "form-control";
			$this->CelTutor->EditCustomAttributes = "";
			$this->CelTutor->EditValue = ew_HtmlEncode($this->CelTutor->CurrentValue);
			$this->CelTutor->PlaceHolder = ew_RemoveHtml($this->CelTutor->FldCaption());

			// Cue_Establecimiento_Alta
			$this->Cue_Establecimiento_Alta->EditAttrs["class"] = "form-control";
			$this->Cue_Establecimiento_Alta->EditCustomAttributes = "";
			$this->Cue_Establecimiento_Alta->EditValue = ew_HtmlEncode($this->Cue_Establecimiento_Alta->CurrentValue);
			if (strval($this->Cue_Establecimiento_Alta->CurrentValue) <> "") {
				$sFilterWrk = "`Cue_Establecimiento`" . ew_SearchString("=", $this->Cue_Establecimiento_Alta->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `Cue_Establecimiento`, `Cue_Establecimiento` AS `DispFld`, `Nombre_Establecimiento` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `establecimientos_educativos_pase`";
			$sWhereWrk = "";
			$this->Cue_Establecimiento_Alta->LookupFilters = array("dx1" => "`Cue_Establecimiento`", "dx2" => "`Nombre_Establecimiento`");
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Cue_Establecimiento_Alta, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->Cue_Establecimiento_Alta->EditValue = $this->Cue_Establecimiento_Alta->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->Cue_Establecimiento_Alta->EditValue = ew_HtmlEncode($this->Cue_Establecimiento_Alta->CurrentValue);
				}
			} else {
				$this->Cue_Establecimiento_Alta->EditValue = NULL;
			}
			$this->Cue_Establecimiento_Alta->PlaceHolder = ew_RemoveHtml($this->Cue_Establecimiento_Alta->FldCaption());

			// Escuela_Alta
			$this->Escuela_Alta->EditAttrs["class"] = "form-control";
			$this->Escuela_Alta->EditCustomAttributes = "";
			$this->Escuela_Alta->EditValue = ew_HtmlEncode($this->Escuela_Alta->CurrentValue);
			$this->Escuela_Alta->PlaceHolder = ew_RemoveHtml($this->Escuela_Alta->FldCaption());

			// Directivo_Alta
			$this->Directivo_Alta->EditAttrs["class"] = "form-control";
			$this->Directivo_Alta->EditCustomAttributes = "";
			$this->Directivo_Alta->EditValue = ew_HtmlEncode($this->Directivo_Alta->CurrentValue);
			$this->Directivo_Alta->PlaceHolder = ew_RemoveHtml($this->Directivo_Alta->FldCaption());

			// Cuil_Directivo_Alta
			$this->Cuil_Directivo_Alta->EditAttrs["class"] = "form-control";
			$this->Cuil_Directivo_Alta->EditCustomAttributes = "";
			$this->Cuil_Directivo_Alta->EditValue = ew_HtmlEncode($this->Cuil_Directivo_Alta->CurrentValue);
			$this->Cuil_Directivo_Alta->PlaceHolder = ew_RemoveHtml($this->Cuil_Directivo_Alta->FldCaption());

			// Dpto_Esc_alta
			$this->Dpto_Esc_alta->EditAttrs["class"] = "form-control";
			$this->Dpto_Esc_alta->EditCustomAttributes = "";
			if (trim(strval($this->Dpto_Esc_alta->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Nombre`" . ew_SearchString("=", $this->Dpto_Esc_alta->CurrentValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT `Nombre`, `Nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `departamento`";
			$sWhereWrk = "";
			$this->Dpto_Esc_alta->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Dpto_Esc_alta, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->Dpto_Esc_alta->EditValue = $arwrk;

			// Localidad_Esc_Alta
			$this->Localidad_Esc_Alta->EditAttrs["class"] = "form-control";
			$this->Localidad_Esc_Alta->EditCustomAttributes = "";
			if (trim(strval($this->Localidad_Esc_Alta->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Nombre`" . ew_SearchString("=", $this->Localidad_Esc_Alta->CurrentValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT `Nombre`, `Nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `localidades`";
			$sWhereWrk = "";
			$this->Localidad_Esc_Alta->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Localidad_Esc_Alta, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->Localidad_Esc_Alta->EditValue = $arwrk;

			// Domicilio_Esc_Alta
			$this->Domicilio_Esc_Alta->EditAttrs["class"] = "form-control";
			$this->Domicilio_Esc_Alta->EditCustomAttributes = "";
			$this->Domicilio_Esc_Alta->EditValue = ew_HtmlEncode($this->Domicilio_Esc_Alta->CurrentValue);
			$this->Domicilio_Esc_Alta->PlaceHolder = ew_RemoveHtml($this->Domicilio_Esc_Alta->FldCaption());

			// Rte_Alta
			$this->Rte_Alta->EditAttrs["class"] = "form-control";
			$this->Rte_Alta->EditCustomAttributes = "";
			$this->Rte_Alta->EditValue = ew_HtmlEncode($this->Rte_Alta->CurrentValue);
			$this->Rte_Alta->PlaceHolder = ew_RemoveHtml($this->Rte_Alta->FldCaption());

			// Tel_Rte_Alta
			$this->Tel_Rte_Alta->EditAttrs["class"] = "form-control";
			$this->Tel_Rte_Alta->EditCustomAttributes = "";
			$this->Tel_Rte_Alta->EditValue = ew_HtmlEncode($this->Tel_Rte_Alta->CurrentValue);
			$this->Tel_Rte_Alta->PlaceHolder = ew_RemoveHtml($this->Tel_Rte_Alta->FldCaption());

			// Email_Rte_Alta
			$this->Email_Rte_Alta->EditAttrs["class"] = "form-control";
			$this->Email_Rte_Alta->EditCustomAttributes = "";
			$this->Email_Rte_Alta->EditValue = ew_HtmlEncode($this->Email_Rte_Alta->CurrentValue);
			$this->Email_Rte_Alta->PlaceHolder = ew_RemoveHtml($this->Email_Rte_Alta->FldCaption());

			// Serie_Server_Alta
			$this->Serie_Server_Alta->EditAttrs["class"] = "form-control";
			$this->Serie_Server_Alta->EditCustomAttributes = "";
			$this->Serie_Server_Alta->EditValue = ew_HtmlEncode($this->Serie_Server_Alta->CurrentValue);
			$this->Serie_Server_Alta->PlaceHolder = ew_RemoveHtml($this->Serie_Server_Alta->FldCaption());

			// Cue_Establecimiento_Baja
			$this->Cue_Establecimiento_Baja->EditAttrs["class"] = "form-control";
			$this->Cue_Establecimiento_Baja->EditCustomAttributes = "";
			$this->Cue_Establecimiento_Baja->EditValue = ew_HtmlEncode($this->Cue_Establecimiento_Baja->CurrentValue);
			if (strval($this->Cue_Establecimiento_Baja->CurrentValue) <> "") {
				$sFilterWrk = "`Cue_Establecimiento`" . ew_SearchString("=", $this->Cue_Establecimiento_Baja->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `Cue_Establecimiento`, `Cue_Establecimiento` AS `DispFld`, `Nombre_Establecimiento` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `establecimientos_educativos_pase`";
			$sWhereWrk = "";
			$this->Cue_Establecimiento_Baja->LookupFilters = array("dx1" => "`Cue_Establecimiento`", "dx2" => "`Nombre_Establecimiento`");
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Cue_Establecimiento_Baja, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->Cue_Establecimiento_Baja->EditValue = $this->Cue_Establecimiento_Baja->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->Cue_Establecimiento_Baja->EditValue = ew_HtmlEncode($this->Cue_Establecimiento_Baja->CurrentValue);
				}
			} else {
				$this->Cue_Establecimiento_Baja->EditValue = NULL;
			}
			$this->Cue_Establecimiento_Baja->PlaceHolder = ew_RemoveHtml($this->Cue_Establecimiento_Baja->FldCaption());

			// Escuela_Baja
			$this->Escuela_Baja->EditAttrs["class"] = "form-control";
			$this->Escuela_Baja->EditCustomAttributes = "";
			$this->Escuela_Baja->EditValue = ew_HtmlEncode($this->Escuela_Baja->CurrentValue);
			$this->Escuela_Baja->PlaceHolder = ew_RemoveHtml($this->Escuela_Baja->FldCaption());

			// Directivo_Baja
			$this->Directivo_Baja->EditAttrs["class"] = "form-control";
			$this->Directivo_Baja->EditCustomAttributes = "";
			$this->Directivo_Baja->EditValue = ew_HtmlEncode($this->Directivo_Baja->CurrentValue);
			$this->Directivo_Baja->PlaceHolder = ew_RemoveHtml($this->Directivo_Baja->FldCaption());

			// Cuil_Directivo_Baja
			$this->Cuil_Directivo_Baja->EditAttrs["class"] = "form-control";
			$this->Cuil_Directivo_Baja->EditCustomAttributes = "";
			$this->Cuil_Directivo_Baja->EditValue = ew_HtmlEncode($this->Cuil_Directivo_Baja->CurrentValue);
			$this->Cuil_Directivo_Baja->PlaceHolder = ew_RemoveHtml($this->Cuil_Directivo_Baja->FldCaption());

			// Dpto_Esc_Baja
			$this->Dpto_Esc_Baja->EditAttrs["class"] = "form-control";
			$this->Dpto_Esc_Baja->EditCustomAttributes = "";
			if (trim(strval($this->Dpto_Esc_Baja->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Nombre`" . ew_SearchString("=", $this->Dpto_Esc_Baja->CurrentValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT `Nombre`, `Nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `departamento`";
			$sWhereWrk = "";
			$this->Dpto_Esc_Baja->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Dpto_Esc_Baja, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->Dpto_Esc_Baja->EditValue = $arwrk;

			// Localidad_Esc_Baja
			$this->Localidad_Esc_Baja->EditAttrs["class"] = "form-control";
			$this->Localidad_Esc_Baja->EditCustomAttributes = "";
			if (trim(strval($this->Localidad_Esc_Baja->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Nombre`" . ew_SearchString("=", $this->Localidad_Esc_Baja->CurrentValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT `Nombre`, `Nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `localidades`";
			$sWhereWrk = "";
			$this->Localidad_Esc_Baja->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Localidad_Esc_Baja, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->Localidad_Esc_Baja->EditValue = $arwrk;

			// Domicilio_Esc_Baja
			$this->Domicilio_Esc_Baja->EditAttrs["class"] = "form-control";
			$this->Domicilio_Esc_Baja->EditCustomAttributes = "";
			$this->Domicilio_Esc_Baja->EditValue = ew_HtmlEncode($this->Domicilio_Esc_Baja->CurrentValue);
			$this->Domicilio_Esc_Baja->PlaceHolder = ew_RemoveHtml($this->Domicilio_Esc_Baja->FldCaption());

			// Rte_Baja
			$this->Rte_Baja->EditAttrs["class"] = "form-control";
			$this->Rte_Baja->EditCustomAttributes = "";
			$this->Rte_Baja->EditValue = ew_HtmlEncode($this->Rte_Baja->CurrentValue);
			$this->Rte_Baja->PlaceHolder = ew_RemoveHtml($this->Rte_Baja->FldCaption());

			// Tel_Rte_Baja
			$this->Tel_Rte_Baja->EditAttrs["class"] = "form-control";
			$this->Tel_Rte_Baja->EditCustomAttributes = "";
			$this->Tel_Rte_Baja->EditValue = ew_HtmlEncode($this->Tel_Rte_Baja->CurrentValue);
			$this->Tel_Rte_Baja->PlaceHolder = ew_RemoveHtml($this->Tel_Rte_Baja->FldCaption());

			// Email_Rte_Baja
			$this->Email_Rte_Baja->EditAttrs["class"] = "form-control";
			$this->Email_Rte_Baja->EditCustomAttributes = "";
			$this->Email_Rte_Baja->EditValue = ew_HtmlEncode($this->Email_Rte_Baja->CurrentValue);
			$this->Email_Rte_Baja->PlaceHolder = ew_RemoveHtml($this->Email_Rte_Baja->FldCaption());

			// Serie_Server_Baja
			$this->Serie_Server_Baja->EditAttrs["class"] = "form-control";
			$this->Serie_Server_Baja->EditCustomAttributes = "";
			$this->Serie_Server_Baja->EditValue = ew_HtmlEncode($this->Serie_Server_Baja->CurrentValue);
			$this->Serie_Server_Baja->PlaceHolder = ew_RemoveHtml($this->Serie_Server_Baja->FldCaption());

			// Fecha_Pase
			$this->Fecha_Pase->EditAttrs["class"] = "form-control";
			$this->Fecha_Pase->EditCustomAttributes = "";
			$this->Fecha_Pase->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->Fecha_Pase->CurrentValue, 7));
			$this->Fecha_Pase->PlaceHolder = ew_RemoveHtml($this->Fecha_Pase->FldCaption());

			// Id_Estado_Pase
			$this->Id_Estado_Pase->EditAttrs["class"] = "form-control";
			$this->Id_Estado_Pase->EditCustomAttributes = "";
			if (trim(strval($this->Id_Estado_Pase->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id_Estado_Pase`" . ew_SearchString("=", $this->Id_Estado_Pase->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `Id_Estado_Pase`, `Descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `estado_pase`";
			$sWhereWrk = "";
			$this->Id_Estado_Pase->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Id_Estado_Pase, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->Id_Estado_Pase->EditValue = $arwrk;

			// Ruta_Archivo
			$this->Ruta_Archivo->EditAttrs["class"] = "form-control";
			$this->Ruta_Archivo->EditCustomAttributes = "";
			$this->Ruta_Archivo->UploadPath = 'ArchivosPase';
			if (!ew_Empty($this->Ruta_Archivo->Upload->DbValue)) {
				$this->Ruta_Archivo->EditValue = $this->Ruta_Archivo->Upload->DbValue;
			} else {
				$this->Ruta_Archivo->EditValue = "";
			}
			if (!ew_Empty($this->Ruta_Archivo->CurrentValue))
				$this->Ruta_Archivo->Upload->FileName = $this->Ruta_Archivo->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->Ruta_Archivo);

			// Add refer script
			// Serie_Equipo

			$this->Serie_Equipo->LinkCustomAttributes = "";
			$this->Serie_Equipo->HrefValue = "";

			// Id_Hardware
			$this->Id_Hardware->LinkCustomAttributes = "";
			$this->Id_Hardware->HrefValue = "";

			// SN
			$this->SN->LinkCustomAttributes = "";
			$this->SN->HrefValue = "";

			// Modelo_Net
			$this->Modelo_Net->LinkCustomAttributes = "";
			$this->Modelo_Net->HrefValue = "";

			// Marca_Arranque
			$this->Marca_Arranque->LinkCustomAttributes = "";
			$this->Marca_Arranque->HrefValue = "";

			// Nombre_Titular
			$this->Nombre_Titular->LinkCustomAttributes = "";
			$this->Nombre_Titular->HrefValue = "";

			// Dni_Titular
			$this->Dni_Titular->LinkCustomAttributes = "";
			$this->Dni_Titular->HrefValue = "";

			// Cuil_Titular
			$this->Cuil_Titular->LinkCustomAttributes = "";
			$this->Cuil_Titular->HrefValue = "";

			// Nombre_Tutor
			$this->Nombre_Tutor->LinkCustomAttributes = "";
			$this->Nombre_Tutor->HrefValue = "";

			// DniTutor
			$this->DniTutor->LinkCustomAttributes = "";
			$this->DniTutor->HrefValue = "";

			// Domicilio
			$this->Domicilio->LinkCustomAttributes = "";
			$this->Domicilio->HrefValue = "";

			// Tel_Tutor
			$this->Tel_Tutor->LinkCustomAttributes = "";
			$this->Tel_Tutor->HrefValue = "";

			// CelTutor
			$this->CelTutor->LinkCustomAttributes = "";
			$this->CelTutor->HrefValue = "";

			// Cue_Establecimiento_Alta
			$this->Cue_Establecimiento_Alta->LinkCustomAttributes = "";
			$this->Cue_Establecimiento_Alta->HrefValue = "";

			// Escuela_Alta
			$this->Escuela_Alta->LinkCustomAttributes = "";
			$this->Escuela_Alta->HrefValue = "";

			// Directivo_Alta
			$this->Directivo_Alta->LinkCustomAttributes = "";
			$this->Directivo_Alta->HrefValue = "";

			// Cuil_Directivo_Alta
			$this->Cuil_Directivo_Alta->LinkCustomAttributes = "";
			$this->Cuil_Directivo_Alta->HrefValue = "";

			// Dpto_Esc_alta
			$this->Dpto_Esc_alta->LinkCustomAttributes = "";
			$this->Dpto_Esc_alta->HrefValue = "";

			// Localidad_Esc_Alta
			$this->Localidad_Esc_Alta->LinkCustomAttributes = "";
			$this->Localidad_Esc_Alta->HrefValue = "";

			// Domicilio_Esc_Alta
			$this->Domicilio_Esc_Alta->LinkCustomAttributes = "";
			$this->Domicilio_Esc_Alta->HrefValue = "";

			// Rte_Alta
			$this->Rte_Alta->LinkCustomAttributes = "";
			$this->Rte_Alta->HrefValue = "";

			// Tel_Rte_Alta
			$this->Tel_Rte_Alta->LinkCustomAttributes = "";
			$this->Tel_Rte_Alta->HrefValue = "";

			// Email_Rte_Alta
			$this->Email_Rte_Alta->LinkCustomAttributes = "";
			$this->Email_Rte_Alta->HrefValue = "";

			// Serie_Server_Alta
			$this->Serie_Server_Alta->LinkCustomAttributes = "";
			$this->Serie_Server_Alta->HrefValue = "";

			// Cue_Establecimiento_Baja
			$this->Cue_Establecimiento_Baja->LinkCustomAttributes = "";
			$this->Cue_Establecimiento_Baja->HrefValue = "";

			// Escuela_Baja
			$this->Escuela_Baja->LinkCustomAttributes = "";
			$this->Escuela_Baja->HrefValue = "";

			// Directivo_Baja
			$this->Directivo_Baja->LinkCustomAttributes = "";
			$this->Directivo_Baja->HrefValue = "";

			// Cuil_Directivo_Baja
			$this->Cuil_Directivo_Baja->LinkCustomAttributes = "";
			$this->Cuil_Directivo_Baja->HrefValue = "";

			// Dpto_Esc_Baja
			$this->Dpto_Esc_Baja->LinkCustomAttributes = "";
			$this->Dpto_Esc_Baja->HrefValue = "";

			// Localidad_Esc_Baja
			$this->Localidad_Esc_Baja->LinkCustomAttributes = "";
			$this->Localidad_Esc_Baja->HrefValue = "";

			// Domicilio_Esc_Baja
			$this->Domicilio_Esc_Baja->LinkCustomAttributes = "";
			$this->Domicilio_Esc_Baja->HrefValue = "";

			// Rte_Baja
			$this->Rte_Baja->LinkCustomAttributes = "";
			$this->Rte_Baja->HrefValue = "";

			// Tel_Rte_Baja
			$this->Tel_Rte_Baja->LinkCustomAttributes = "";
			$this->Tel_Rte_Baja->HrefValue = "";

			// Email_Rte_Baja
			$this->Email_Rte_Baja->LinkCustomAttributes = "";
			$this->Email_Rte_Baja->HrefValue = "";

			// Serie_Server_Baja
			$this->Serie_Server_Baja->LinkCustomAttributes = "";
			$this->Serie_Server_Baja->HrefValue = "";

			// Fecha_Pase
			$this->Fecha_Pase->LinkCustomAttributes = "";
			$this->Fecha_Pase->HrefValue = "";

			// Id_Estado_Pase
			$this->Id_Estado_Pase->LinkCustomAttributes = "";
			$this->Id_Estado_Pase->HrefValue = "";

			// Ruta_Archivo
			$this->Ruta_Archivo->LinkCustomAttributes = "";
			$this->Ruta_Archivo->HrefValue = "";
			$this->Ruta_Archivo->HrefValue2 = $this->Ruta_Archivo->UploadPath . $this->Ruta_Archivo->Upload->DbValue;
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

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->Serie_Equipo->FldIsDetailKey && !is_null($this->Serie_Equipo->FormValue) && $this->Serie_Equipo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Serie_Equipo->FldCaption(), $this->Serie_Equipo->ReqErrMsg));
		}
		if (!$this->Id_Hardware->FldIsDetailKey && !is_null($this->Id_Hardware->FormValue) && $this->Id_Hardware->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Id_Hardware->FldCaption(), $this->Id_Hardware->ReqErrMsg));
		}
		if (!$this->Modelo_Net->FldIsDetailKey && !is_null($this->Modelo_Net->FormValue) && $this->Modelo_Net->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Modelo_Net->FldCaption(), $this->Modelo_Net->ReqErrMsg));
		}
		if (!$this->Marca_Arranque->FldIsDetailKey && !is_null($this->Marca_Arranque->FormValue) && $this->Marca_Arranque->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Marca_Arranque->FldCaption(), $this->Marca_Arranque->ReqErrMsg));
		}
		if (!$this->Nombre_Titular->FldIsDetailKey && !is_null($this->Nombre_Titular->FormValue) && $this->Nombre_Titular->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Nombre_Titular->FldCaption(), $this->Nombre_Titular->ReqErrMsg));
		}
		if (!$this->Dni_Titular->FldIsDetailKey && !is_null($this->Dni_Titular->FormValue) && $this->Dni_Titular->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Dni_Titular->FldCaption(), $this->Dni_Titular->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->Dni_Titular->FormValue)) {
			ew_AddMessage($gsFormError, $this->Dni_Titular->FldErrMsg());
		}
		if (!$this->Cuil_Titular->FldIsDetailKey && !is_null($this->Cuil_Titular->FormValue) && $this->Cuil_Titular->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Cuil_Titular->FldCaption(), $this->Cuil_Titular->ReqErrMsg));
		}
		if (!$this->Nombre_Tutor->FldIsDetailKey && !is_null($this->Nombre_Tutor->FormValue) && $this->Nombre_Tutor->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Nombre_Tutor->FldCaption(), $this->Nombre_Tutor->ReqErrMsg));
		}
		if (!$this->DniTutor->FldIsDetailKey && !is_null($this->DniTutor->FormValue) && $this->DniTutor->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->DniTutor->FldCaption(), $this->DniTutor->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->DniTutor->FormValue)) {
			ew_AddMessage($gsFormError, $this->DniTutor->FldErrMsg());
		}
		if (!$this->Domicilio->FldIsDetailKey && !is_null($this->Domicilio->FormValue) && $this->Domicilio->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Domicilio->FldCaption(), $this->Domicilio->ReqErrMsg));
		}
		if (!$this->Cue_Establecimiento_Alta->FldIsDetailKey && !is_null($this->Cue_Establecimiento_Alta->FormValue) && $this->Cue_Establecimiento_Alta->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Cue_Establecimiento_Alta->FldCaption(), $this->Cue_Establecimiento_Alta->ReqErrMsg));
		}
		if (!$this->Escuela_Alta->FldIsDetailKey && !is_null($this->Escuela_Alta->FormValue) && $this->Escuela_Alta->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Escuela_Alta->FldCaption(), $this->Escuela_Alta->ReqErrMsg));
		}
		if (!$this->Directivo_Alta->FldIsDetailKey && !is_null($this->Directivo_Alta->FormValue) && $this->Directivo_Alta->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Directivo_Alta->FldCaption(), $this->Directivo_Alta->ReqErrMsg));
		}
		if (!$this->Cuil_Directivo_Alta->FldIsDetailKey && !is_null($this->Cuil_Directivo_Alta->FormValue) && $this->Cuil_Directivo_Alta->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Cuil_Directivo_Alta->FldCaption(), $this->Cuil_Directivo_Alta->ReqErrMsg));
		}
		if (!$this->Dpto_Esc_alta->FldIsDetailKey && !is_null($this->Dpto_Esc_alta->FormValue) && $this->Dpto_Esc_alta->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Dpto_Esc_alta->FldCaption(), $this->Dpto_Esc_alta->ReqErrMsg));
		}
		if (!$this->Localidad_Esc_Alta->FldIsDetailKey && !is_null($this->Localidad_Esc_Alta->FormValue) && $this->Localidad_Esc_Alta->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Localidad_Esc_Alta->FldCaption(), $this->Localidad_Esc_Alta->ReqErrMsg));
		}
		if (!$this->Domicilio_Esc_Alta->FldIsDetailKey && !is_null($this->Domicilio_Esc_Alta->FormValue) && $this->Domicilio_Esc_Alta->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Domicilio_Esc_Alta->FldCaption(), $this->Domicilio_Esc_Alta->ReqErrMsg));
		}
		if (!$this->Rte_Alta->FldIsDetailKey && !is_null($this->Rte_Alta->FormValue) && $this->Rte_Alta->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Rte_Alta->FldCaption(), $this->Rte_Alta->ReqErrMsg));
		}
		if (!$this->Tel_Rte_Alta->FldIsDetailKey && !is_null($this->Tel_Rte_Alta->FormValue) && $this->Tel_Rte_Alta->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Tel_Rte_Alta->FldCaption(), $this->Tel_Rte_Alta->ReqErrMsg));
		}
		if (!$this->Email_Rte_Alta->FldIsDetailKey && !is_null($this->Email_Rte_Alta->FormValue) && $this->Email_Rte_Alta->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Email_Rte_Alta->FldCaption(), $this->Email_Rte_Alta->ReqErrMsg));
		}
		if (!$this->Serie_Server_Alta->FldIsDetailKey && !is_null($this->Serie_Server_Alta->FormValue) && $this->Serie_Server_Alta->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Serie_Server_Alta->FldCaption(), $this->Serie_Server_Alta->ReqErrMsg));
		}
		if (!$this->Cue_Establecimiento_Baja->FldIsDetailKey && !is_null($this->Cue_Establecimiento_Baja->FormValue) && $this->Cue_Establecimiento_Baja->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Cue_Establecimiento_Baja->FldCaption(), $this->Cue_Establecimiento_Baja->ReqErrMsg));
		}
		if (!$this->Escuela_Baja->FldIsDetailKey && !is_null($this->Escuela_Baja->FormValue) && $this->Escuela_Baja->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Escuela_Baja->FldCaption(), $this->Escuela_Baja->ReqErrMsg));
		}
		if (!$this->Directivo_Baja->FldIsDetailKey && !is_null($this->Directivo_Baja->FormValue) && $this->Directivo_Baja->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Directivo_Baja->FldCaption(), $this->Directivo_Baja->ReqErrMsg));
		}
		if (!$this->Cuil_Directivo_Baja->FldIsDetailKey && !is_null($this->Cuil_Directivo_Baja->FormValue) && $this->Cuil_Directivo_Baja->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Cuil_Directivo_Baja->FldCaption(), $this->Cuil_Directivo_Baja->ReqErrMsg));
		}
		if (!$this->Dpto_Esc_Baja->FldIsDetailKey && !is_null($this->Dpto_Esc_Baja->FormValue) && $this->Dpto_Esc_Baja->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Dpto_Esc_Baja->FldCaption(), $this->Dpto_Esc_Baja->ReqErrMsg));
		}
		if (!$this->Localidad_Esc_Baja->FldIsDetailKey && !is_null($this->Localidad_Esc_Baja->FormValue) && $this->Localidad_Esc_Baja->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Localidad_Esc_Baja->FldCaption(), $this->Localidad_Esc_Baja->ReqErrMsg));
		}
		if (!$this->Domicilio_Esc_Baja->FldIsDetailKey && !is_null($this->Domicilio_Esc_Baja->FormValue) && $this->Domicilio_Esc_Baja->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Domicilio_Esc_Baja->FldCaption(), $this->Domicilio_Esc_Baja->ReqErrMsg));
		}
		if (!$this->Rte_Baja->FldIsDetailKey && !is_null($this->Rte_Baja->FormValue) && $this->Rte_Baja->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Rte_Baja->FldCaption(), $this->Rte_Baja->ReqErrMsg));
		}
		if (!$this->Tel_Rte_Baja->FldIsDetailKey && !is_null($this->Tel_Rte_Baja->FormValue) && $this->Tel_Rte_Baja->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Tel_Rte_Baja->FldCaption(), $this->Tel_Rte_Baja->ReqErrMsg));
		}
		if (!$this->Email_Rte_Baja->FldIsDetailKey && !is_null($this->Email_Rte_Baja->FormValue) && $this->Email_Rte_Baja->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Email_Rte_Baja->FldCaption(), $this->Email_Rte_Baja->ReqErrMsg));
		}
		if (!$this->Serie_Server_Baja->FldIsDetailKey && !is_null($this->Serie_Server_Baja->FormValue) && $this->Serie_Server_Baja->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Serie_Server_Baja->FldCaption(), $this->Serie_Server_Baja->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->Fecha_Pase->FormValue)) {
			ew_AddMessage($gsFormError, $this->Fecha_Pase->FldErrMsg());
		}
		if (!$this->Id_Estado_Pase->FldIsDetailKey && !is_null($this->Id_Estado_Pase->FormValue) && $this->Id_Estado_Pase->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Id_Estado_Pase->FldCaption(), $this->Id_Estado_Pase->ReqErrMsg));
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
			$this->Ruta_Archivo->OldUploadPath = 'ArchivosPase';
			$this->Ruta_Archivo->UploadPath = $this->Ruta_Archivo->OldUploadPath;
		}
		$rsnew = array();

		// Serie_Equipo
		$this->Serie_Equipo->SetDbValueDef($rsnew, $this->Serie_Equipo->CurrentValue, NULL, FALSE);

		// Id_Hardware
		$this->Id_Hardware->SetDbValueDef($rsnew, $this->Id_Hardware->CurrentValue, NULL, FALSE);

		// SN
		$this->SN->SetDbValueDef($rsnew, $this->SN->CurrentValue, NULL, FALSE);

		// Modelo_Net
		$this->Modelo_Net->SetDbValueDef($rsnew, $this->Modelo_Net->CurrentValue, NULL, FALSE);

		// Marca_Arranque
		$this->Marca_Arranque->SetDbValueDef($rsnew, $this->Marca_Arranque->CurrentValue, NULL, FALSE);

		// Nombre_Titular
		$this->Nombre_Titular->SetDbValueDef($rsnew, $this->Nombre_Titular->CurrentValue, NULL, FALSE);

		// Dni_Titular
		$this->Dni_Titular->SetDbValueDef($rsnew, $this->Dni_Titular->CurrentValue, NULL, FALSE);

		// Cuil_Titular
		$this->Cuil_Titular->SetDbValueDef($rsnew, $this->Cuil_Titular->CurrentValue, NULL, FALSE);

		// Nombre_Tutor
		$this->Nombre_Tutor->SetDbValueDef($rsnew, $this->Nombre_Tutor->CurrentValue, NULL, FALSE);

		// DniTutor
		$this->DniTutor->SetDbValueDef($rsnew, $this->DniTutor->CurrentValue, NULL, FALSE);

		// Domicilio
		$this->Domicilio->SetDbValueDef($rsnew, $this->Domicilio->CurrentValue, NULL, FALSE);

		// Tel_Tutor
		$this->Tel_Tutor->SetDbValueDef($rsnew, $this->Tel_Tutor->CurrentValue, NULL, FALSE);

		// CelTutor
		$this->CelTutor->SetDbValueDef($rsnew, $this->CelTutor->CurrentValue, NULL, FALSE);

		// Cue_Establecimiento_Alta
		$this->Cue_Establecimiento_Alta->SetDbValueDef($rsnew, $this->Cue_Establecimiento_Alta->CurrentValue, NULL, FALSE);

		// Escuela_Alta
		$this->Escuela_Alta->SetDbValueDef($rsnew, $this->Escuela_Alta->CurrentValue, NULL, FALSE);

		// Directivo_Alta
		$this->Directivo_Alta->SetDbValueDef($rsnew, $this->Directivo_Alta->CurrentValue, NULL, FALSE);

		// Cuil_Directivo_Alta
		$this->Cuil_Directivo_Alta->SetDbValueDef($rsnew, $this->Cuil_Directivo_Alta->CurrentValue, NULL, FALSE);

		// Dpto_Esc_alta
		$this->Dpto_Esc_alta->SetDbValueDef($rsnew, $this->Dpto_Esc_alta->CurrentValue, NULL, FALSE);

		// Localidad_Esc_Alta
		$this->Localidad_Esc_Alta->SetDbValueDef($rsnew, $this->Localidad_Esc_Alta->CurrentValue, NULL, FALSE);

		// Domicilio_Esc_Alta
		$this->Domicilio_Esc_Alta->SetDbValueDef($rsnew, $this->Domicilio_Esc_Alta->CurrentValue, NULL, FALSE);

		// Rte_Alta
		$this->Rte_Alta->SetDbValueDef($rsnew, $this->Rte_Alta->CurrentValue, NULL, FALSE);

		// Tel_Rte_Alta
		$this->Tel_Rte_Alta->SetDbValueDef($rsnew, $this->Tel_Rte_Alta->CurrentValue, NULL, FALSE);

		// Email_Rte_Alta
		$this->Email_Rte_Alta->SetDbValueDef($rsnew, $this->Email_Rte_Alta->CurrentValue, NULL, FALSE);

		// Serie_Server_Alta
		$this->Serie_Server_Alta->SetDbValueDef($rsnew, $this->Serie_Server_Alta->CurrentValue, NULL, FALSE);

		// Cue_Establecimiento_Baja
		$this->Cue_Establecimiento_Baja->SetDbValueDef($rsnew, $this->Cue_Establecimiento_Baja->CurrentValue, NULL, FALSE);

		// Escuela_Baja
		$this->Escuela_Baja->SetDbValueDef($rsnew, $this->Escuela_Baja->CurrentValue, NULL, FALSE);

		// Directivo_Baja
		$this->Directivo_Baja->SetDbValueDef($rsnew, $this->Directivo_Baja->CurrentValue, NULL, FALSE);

		// Cuil_Directivo_Baja
		$this->Cuil_Directivo_Baja->SetDbValueDef($rsnew, $this->Cuil_Directivo_Baja->CurrentValue, NULL, FALSE);

		// Dpto_Esc_Baja
		$this->Dpto_Esc_Baja->SetDbValueDef($rsnew, $this->Dpto_Esc_Baja->CurrentValue, NULL, FALSE);

		// Localidad_Esc_Baja
		$this->Localidad_Esc_Baja->SetDbValueDef($rsnew, $this->Localidad_Esc_Baja->CurrentValue, NULL, FALSE);

		// Domicilio_Esc_Baja
		$this->Domicilio_Esc_Baja->SetDbValueDef($rsnew, $this->Domicilio_Esc_Baja->CurrentValue, NULL, FALSE);

		// Rte_Baja
		$this->Rte_Baja->SetDbValueDef($rsnew, $this->Rte_Baja->CurrentValue, NULL, FALSE);

		// Tel_Rte_Baja
		$this->Tel_Rte_Baja->SetDbValueDef($rsnew, $this->Tel_Rte_Baja->CurrentValue, NULL, FALSE);

		// Email_Rte_Baja
		$this->Email_Rte_Baja->SetDbValueDef($rsnew, $this->Email_Rte_Baja->CurrentValue, NULL, FALSE);

		// Serie_Server_Baja
		$this->Serie_Server_Baja->SetDbValueDef($rsnew, $this->Serie_Server_Baja->CurrentValue, NULL, FALSE);

		// Fecha_Pase
		$this->Fecha_Pase->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->Fecha_Pase->CurrentValue, 7), NULL, FALSE);

		// Id_Estado_Pase
		$this->Id_Estado_Pase->SetDbValueDef($rsnew, $this->Id_Estado_Pase->CurrentValue, 0, FALSE);

		// Ruta_Archivo
		if ($this->Ruta_Archivo->Visible && !$this->Ruta_Archivo->Upload->KeepFile) {
			$this->Ruta_Archivo->Upload->DbValue = ""; // No need to delete old file
			if ($this->Ruta_Archivo->Upload->FileName == "") {
				$rsnew['Ruta_Archivo'] = NULL;
			} else {
				$rsnew['Ruta_Archivo'] = $this->Ruta_Archivo->Upload->FileName;
			}
		}
		if ($this->Ruta_Archivo->Visible && !$this->Ruta_Archivo->Upload->KeepFile) {
			$this->Ruta_Archivo->UploadPath = 'ArchivosPase';
			$OldFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $this->Ruta_Archivo->Upload->DbValue);
			if (!ew_Empty($this->Ruta_Archivo->Upload->FileName)) {
				$NewFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $this->Ruta_Archivo->Upload->FileName);
				$FileCount = count($NewFiles);
				for ($i = 0; $i < $FileCount; $i++) {
					$fldvar = ($this->Ruta_Archivo->Upload->Index < 0) ? $this->Ruta_Archivo->FldVar : substr($this->Ruta_Archivo->FldVar, 0, 1) . $this->Ruta_Archivo->Upload->Index . substr($this->Ruta_Archivo->FldVar, 1);
					if ($NewFiles[$i] <> "") {
						$file = $NewFiles[$i];
						if (file_exists(ew_UploadTempPath($fldvar, $this->Ruta_Archivo->TblVar) . EW_PATH_DELIMITER . $file)) {
							if (!in_array($file, $OldFiles)) {
								$file1 = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->Ruta_Archivo->UploadPath), $file); // Get new file name
								if ($file1 <> $file) { // Rename temp file
									while (file_exists(ew_UploadTempPath($fldvar, $this->Ruta_Archivo->TblVar) . EW_PATH_DELIMITER . $file1)) // Make sure did not clash with existing upload file
										$file1 = ew_UniqueFilename(ew_UploadPathEx(TRUE, $this->Ruta_Archivo->UploadPath), $file1, TRUE); // Use indexed name
									rename(ew_UploadTempPath($fldvar, $this->Ruta_Archivo->TblVar) . EW_PATH_DELIMITER . $file, ew_UploadTempPath($fldvar, $this->Ruta_Archivo->TblVar) . EW_PATH_DELIMITER . $file1);
									$NewFiles[$i] = $file1;
								}
							}
						}
					}
				}
				$this->Ruta_Archivo->Upload->FileName = implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $NewFiles);
				$rsnew['Ruta_Archivo'] = $this->Ruta_Archivo->Upload->FileName;
			} else {
				$NewFiles = array();
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->Id_Pase->setDbValue($conn->Insert_ID());
				$rsnew['Id_Pase'] = $this->Id_Pase->DbValue;
				if ($this->Ruta_Archivo->Visible && !$this->Ruta_Archivo->Upload->KeepFile) {
					$OldFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $this->Ruta_Archivo->Upload->DbValue);
					if (!ew_Empty($this->Ruta_Archivo->Upload->FileName)) {
						$NewFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $this->Ruta_Archivo->Upload->FileName);
						$NewFiles2 = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $rsnew['Ruta_Archivo']);
						$FileCount = count($NewFiles);
						for ($i = 0; $i < $FileCount; $i++) {
							$fldvar = ($this->Ruta_Archivo->Upload->Index < 0) ? $this->Ruta_Archivo->FldVar : substr($this->Ruta_Archivo->FldVar, 0, 1) . $this->Ruta_Archivo->Upload->Index . substr($this->Ruta_Archivo->FldVar, 1);
							if ($NewFiles[$i] <> "") {
								$file = ew_UploadTempPath($fldvar, $this->Ruta_Archivo->TblVar) . EW_PATH_DELIMITER . $NewFiles[$i];
								if (file_exists($file)) {
									$this->Ruta_Archivo->Upload->SaveToFile($this->Ruta_Archivo->UploadPath, (@$NewFiles2[$i] <> "") ? $NewFiles2[$i] : $NewFiles[$i], TRUE, $i); // Just replace
								}
							}
						}
					} else {
						$NewFiles = array();
					}
					$FileCount = count($OldFiles);
					for ($i = 0; $i < $FileCount; $i++) {
						if ($OldFiles[$i] <> "" && !in_array($OldFiles[$i], $NewFiles))
							@unlink(ew_UploadPathEx(TRUE, $this->Ruta_Archivo->OldUploadPath) . $OldFiles[$i]);
					}
				}
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
			$this->WriteAuditTrailOnAdd($rsnew);
		}

		// Ruta_Archivo
		ew_CleanUploadTempPath($this->Ruta_Archivo, $this->Ruta_Archivo->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("pase_establecimientolist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Set up multi pages
	function SetupMultiPages() {
		$pages = new cSubPages();
		$pages->Add(0);
		$pages->Add(1);
		$pages->Add(2);
		$pages->Add(3);
		$pages->Add(4);
		$pages->Add(5);
		$pages->Add(6);
		$this->MultiPages = $pages;
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_Serie_Equipo":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `NroSerie` AS `LinkFld`, `NroSerie` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `equipos`";
			$sWhereWrk = "{filter}";
			$this->Serie_Equipo->LookupFilters = array("dx1" => "`NroSerie`");
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`NroSerie` = {filter_value}", "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Serie_Equipo, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_Modelo_Net":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Descripcion` AS `LinkFld`, `Descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `modelo`";
			$sWhereWrk = "";
			$this->Modelo_Net->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`Descripcion` = {filter_value}", "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Modelo_Net, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_Nombre_Titular":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Apellidos_Nombres` AS `LinkFld`, `Apellidos_Nombres` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `personas`";
			$sWhereWrk = "{filter}";
			$this->Nombre_Titular->LookupFilters = array("dx1" => "`Apellidos_Nombres`");
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`Apellidos_Nombres` = {filter_value}", "t0" => "201", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Nombre_Titular, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_Nombre_Tutor":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Apellidos_Nombres` AS `LinkFld`, `Apellidos_Nombres` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tutores`";
			$sWhereWrk = "{filter}";
			$this->Nombre_Tutor->LookupFilters = array("dx1" => "`Apellidos_Nombres`");
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`Apellidos_Nombres` = {filter_value}", "t0" => "201", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Nombre_Tutor, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_Cue_Establecimiento_Alta":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Cue_Establecimiento` AS `LinkFld`, `Cue_Establecimiento` AS `DispFld`, `Nombre_Establecimiento` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `establecimientos_educativos_pase`";
			$sWhereWrk = "{filter}";
			$this->Cue_Establecimiento_Alta->LookupFilters = array("dx1" => "`Cue_Establecimiento`", "dx2" => "`Nombre_Establecimiento`");
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`Cue_Establecimiento` = {filter_value}", "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Cue_Establecimiento_Alta, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_Dpto_Esc_alta":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Nombre` AS `LinkFld`, `Nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departamento`";
			$sWhereWrk = "";
			$this->Dpto_Esc_alta->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`Nombre` = {filter_value}", "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Dpto_Esc_alta, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_Localidad_Esc_Alta":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Nombre` AS `LinkFld`, `Nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `localidades`";
			$sWhereWrk = "";
			$this->Localidad_Esc_Alta->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`Nombre` = {filter_value}", "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Localidad_Esc_Alta, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_Cue_Establecimiento_Baja":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Cue_Establecimiento` AS `LinkFld`, `Cue_Establecimiento` AS `DispFld`, `Nombre_Establecimiento` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `establecimientos_educativos_pase`";
			$sWhereWrk = "{filter}";
			$this->Cue_Establecimiento_Baja->LookupFilters = array("dx1" => "`Cue_Establecimiento`", "dx2" => "`Nombre_Establecimiento`");
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`Cue_Establecimiento` = {filter_value}", "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Cue_Establecimiento_Baja, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_Dpto_Esc_Baja":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Nombre` AS `LinkFld`, `Nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departamento`";
			$sWhereWrk = "";
			$this->Dpto_Esc_Baja->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`Nombre` = {filter_value}", "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Dpto_Esc_Baja, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_Localidad_Esc_Baja":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Nombre` AS `LinkFld`, `Nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `localidades`";
			$sWhereWrk = "";
			$this->Localidad_Esc_Baja->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`Nombre` = {filter_value}", "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Localidad_Esc_Baja, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_Id_Estado_Pase":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Id_Estado_Pase` AS `LinkFld`, `Descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `estado_pase`";
			$sWhereWrk = "";
			$this->Id_Estado_Pase->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`Id_Estado_Pase` = {filter_value}", "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Id_Estado_Pase, $sWhereWrk); // Call Lookup selecting
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
		case "x_Serie_Equipo":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `NroSerie`, `NroSerie` AS `DispFld` FROM `equipos`";
			$sWhereWrk = "`NroSerie` LIKE '{query_value}%'";
			$this->Serie_Equipo->LookupFilters = array("dx1" => "`NroSerie`");
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Serie_Equipo, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_Nombre_Titular":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Apellidos_Nombres`, `Apellidos_Nombres` AS `DispFld` FROM `personas`";
			$sWhereWrk = "`Apellidos_Nombres` LIKE '{query_value}%'";
			$this->Nombre_Titular->LookupFilters = array("dx1" => "`Apellidos_Nombres`");
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Nombre_Titular, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_Nombre_Tutor":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Apellidos_Nombres`, `Apellidos_Nombres` AS `DispFld` FROM `tutores`";
			$sWhereWrk = "`Apellidos_Nombres` LIKE '{query_value}%'";
			$this->Nombre_Tutor->LookupFilters = array("dx1" => "`Apellidos_Nombres`");
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Nombre_Tutor, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_Cue_Establecimiento_Alta":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Cue_Establecimiento`, `Cue_Establecimiento` AS `DispFld`, `Nombre_Establecimiento` AS `Disp2Fld` FROM `establecimientos_educativos_pase`";
			$sWhereWrk = "`Cue_Establecimiento` LIKE '{query_value}%' OR CONCAT(`Cue_Establecimiento`,'" . ew_ValueSeparator(1, $this->Cue_Establecimiento_Alta) . "',`Nombre_Establecimiento`) LIKE '{query_value}%'";
			$this->Cue_Establecimiento_Alta->LookupFilters = array("dx1" => "`Cue_Establecimiento`", "dx2" => "`Nombre_Establecimiento`");
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Cue_Establecimiento_Alta, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_Cue_Establecimiento_Baja":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Cue_Establecimiento`, `Cue_Establecimiento` AS `DispFld`, `Nombre_Establecimiento` AS `Disp2Fld` FROM `establecimientos_educativos_pase`";
			$sWhereWrk = "`Cue_Establecimiento` LIKE '{query_value}%' OR CONCAT(`Cue_Establecimiento`,'" . ew_ValueSeparator(1, $this->Cue_Establecimiento_Baja) . "',`Nombre_Establecimiento`) LIKE '{query_value}%'";
			$this->Cue_Establecimiento_Baja->LookupFilters = array("dx1" => "`Cue_Establecimiento`", "dx2" => "`Nombre_Establecimiento`");
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Cue_Establecimiento_Baja, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		}
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'pase_establecimiento';
		$usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnAdd) return;
		$table = 'pase_establecimiento';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['Id_Pase'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$usr = CurrentUserID();
		foreach (array_keys($rs) as $fldname) {
			if ($this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") {
					$newvalue = $Language->Phrase("PasswordMask"); // Password Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$newvalue = $rs[$fldname];
					else
						$newvalue = "[MEMO]"; // Memo Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$newvalue = "[XML]"; // XML Field
				} else {
					$newvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $usr, "A", $table, $fldname, $key, "", $newvalue);
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
if (!isset($pase_establecimiento_add)) $pase_establecimiento_add = new cpase_establecimiento_add();

// Page init
$pase_establecimiento_add->Page_Init();

// Page main
$pase_establecimiento_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pase_establecimiento_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fpase_establecimientoadd = new ew_Form("fpase_establecimientoadd", "add");

// Validate form
fpase_establecimientoadd.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_Serie_Equipo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->Serie_Equipo->FldCaption(), $pase_establecimiento->Serie_Equipo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Id_Hardware");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->Id_Hardware->FldCaption(), $pase_establecimiento->Id_Hardware->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Modelo_Net");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->Modelo_Net->FldCaption(), $pase_establecimiento->Modelo_Net->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Marca_Arranque");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->Marca_Arranque->FldCaption(), $pase_establecimiento->Marca_Arranque->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Nombre_Titular");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->Nombre_Titular->FldCaption(), $pase_establecimiento->Nombre_Titular->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Dni_Titular");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->Dni_Titular->FldCaption(), $pase_establecimiento->Dni_Titular->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Dni_Titular");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pase_establecimiento->Dni_Titular->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Cuil_Titular");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->Cuil_Titular->FldCaption(), $pase_establecimiento->Cuil_Titular->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Nombre_Tutor");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->Nombre_Tutor->FldCaption(), $pase_establecimiento->Nombre_Tutor->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_DniTutor");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->DniTutor->FldCaption(), $pase_establecimiento->DniTutor->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_DniTutor");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pase_establecimiento->DniTutor->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Domicilio");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->Domicilio->FldCaption(), $pase_establecimiento->Domicilio->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Cue_Establecimiento_Alta");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->Cue_Establecimiento_Alta->FldCaption(), $pase_establecimiento->Cue_Establecimiento_Alta->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Escuela_Alta");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->Escuela_Alta->FldCaption(), $pase_establecimiento->Escuela_Alta->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Directivo_Alta");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->Directivo_Alta->FldCaption(), $pase_establecimiento->Directivo_Alta->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Cuil_Directivo_Alta");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->Cuil_Directivo_Alta->FldCaption(), $pase_establecimiento->Cuil_Directivo_Alta->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Dpto_Esc_alta");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->Dpto_Esc_alta->FldCaption(), $pase_establecimiento->Dpto_Esc_alta->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Localidad_Esc_Alta");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->Localidad_Esc_Alta->FldCaption(), $pase_establecimiento->Localidad_Esc_Alta->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Domicilio_Esc_Alta");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->Domicilio_Esc_Alta->FldCaption(), $pase_establecimiento->Domicilio_Esc_Alta->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Rte_Alta");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->Rte_Alta->FldCaption(), $pase_establecimiento->Rte_Alta->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Tel_Rte_Alta");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->Tel_Rte_Alta->FldCaption(), $pase_establecimiento->Tel_Rte_Alta->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Email_Rte_Alta");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->Email_Rte_Alta->FldCaption(), $pase_establecimiento->Email_Rte_Alta->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Serie_Server_Alta");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->Serie_Server_Alta->FldCaption(), $pase_establecimiento->Serie_Server_Alta->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Cue_Establecimiento_Baja");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->Cue_Establecimiento_Baja->FldCaption(), $pase_establecimiento->Cue_Establecimiento_Baja->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Escuela_Baja");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->Escuela_Baja->FldCaption(), $pase_establecimiento->Escuela_Baja->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Directivo_Baja");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->Directivo_Baja->FldCaption(), $pase_establecimiento->Directivo_Baja->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Cuil_Directivo_Baja");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->Cuil_Directivo_Baja->FldCaption(), $pase_establecimiento->Cuil_Directivo_Baja->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Dpto_Esc_Baja");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->Dpto_Esc_Baja->FldCaption(), $pase_establecimiento->Dpto_Esc_Baja->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Localidad_Esc_Baja");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->Localidad_Esc_Baja->FldCaption(), $pase_establecimiento->Localidad_Esc_Baja->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Domicilio_Esc_Baja");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->Domicilio_Esc_Baja->FldCaption(), $pase_establecimiento->Domicilio_Esc_Baja->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Rte_Baja");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->Rte_Baja->FldCaption(), $pase_establecimiento->Rte_Baja->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Tel_Rte_Baja");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->Tel_Rte_Baja->FldCaption(), $pase_establecimiento->Tel_Rte_Baja->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Email_Rte_Baja");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->Email_Rte_Baja->FldCaption(), $pase_establecimiento->Email_Rte_Baja->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Serie_Server_Baja");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->Serie_Server_Baja->FldCaption(), $pase_establecimiento->Serie_Server_Baja->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Fecha_Pase");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pase_establecimiento->Fecha_Pase->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Id_Estado_Pase");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pase_establecimiento->Id_Estado_Pase->FldCaption(), $pase_establecimiento->Id_Estado_Pase->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fpase_establecimientoadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpase_establecimientoadd.ValidateRequired = true;
<?php } else { ?>
fpase_establecimientoadd.ValidateRequired = false; 
<?php } ?>

// Multi-Page
fpase_establecimientoadd.MultiPage = new ew_MultiPage("fpase_establecimientoadd");

// Dynamic selection lists
fpase_establecimientoadd.Lists["x_Serie_Equipo"] = {"LinkField":"x_NroSerie","Ajax":true,"AutoFill":true,"DisplayFields":["x_NroSerie","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"equipos"};
fpase_establecimientoadd.Lists["x_Modelo_Net"] = {"LinkField":"x_Descripcion","Ajax":true,"AutoFill":false,"DisplayFields":["x_Descripcion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"modelo"};
fpase_establecimientoadd.Lists["x_Nombre_Titular"] = {"LinkField":"x_Apellidos_Nombres","Ajax":true,"AutoFill":true,"DisplayFields":["x_Apellidos_Nombres","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"personas"};
fpase_establecimientoadd.Lists["x_Nombre_Tutor"] = {"LinkField":"x_Apellidos_Nombres","Ajax":true,"AutoFill":true,"DisplayFields":["x_Apellidos_Nombres","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tutores"};
fpase_establecimientoadd.Lists["x_Cue_Establecimiento_Alta"] = {"LinkField":"x_Cue_Establecimiento","Ajax":true,"AutoFill":true,"DisplayFields":["x_Cue_Establecimiento","x_Nombre_Establecimiento","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"establecimientos_educativos_pase"};
fpase_establecimientoadd.Lists["x_Dpto_Esc_alta"] = {"LinkField":"x_Nombre","Ajax":true,"AutoFill":false,"DisplayFields":["x_Nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"departamento"};
fpase_establecimientoadd.Lists["x_Localidad_Esc_Alta"] = {"LinkField":"x_Nombre","Ajax":true,"AutoFill":false,"DisplayFields":["x_Nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"localidades"};
fpase_establecimientoadd.Lists["x_Cue_Establecimiento_Baja"] = {"LinkField":"x_Cue_Establecimiento","Ajax":true,"AutoFill":true,"DisplayFields":["x_Cue_Establecimiento","x_Nombre_Establecimiento","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"establecimientos_educativos_pase"};
fpase_establecimientoadd.Lists["x_Dpto_Esc_Baja"] = {"LinkField":"x_Nombre","Ajax":true,"AutoFill":false,"DisplayFields":["x_Nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"departamento"};
fpase_establecimientoadd.Lists["x_Localidad_Esc_Baja"] = {"LinkField":"x_Nombre","Ajax":true,"AutoFill":false,"DisplayFields":["x_Nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"localidades"};
fpase_establecimientoadd.Lists["x_Id_Estado_Pase"] = {"LinkField":"x_Id_Estado_Pase","Ajax":true,"AutoFill":false,"DisplayFields":["x_Descripcion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"estado_pase"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$pase_establecimiento_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $pase_establecimiento_add->ShowPageHeader(); ?>
<?php
$pase_establecimiento_add->ShowMessage();
?>
<form name="fpase_establecimientoadd" id="fpase_establecimientoadd" class="<?php echo $pase_establecimiento_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pase_establecimiento_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pase_establecimiento_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pase_establecimiento">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($pase_establecimiento_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div class="ewMultiPage">
<div class="panel-group" id="pase_establecimiento_add">
	<div class="panel panel-default<?php echo $pase_establecimiento_add->MultiPages->PageStyle("1") ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a class="panel-toggle" data-toggle="collapse" data-parent="#pase_establecimiento_add" href="#tab_pase_establecimiento1"><?php echo $pase_establecimiento->PageCaption(1) ?></a>
			</h4>
		</div>
		<div class="panel-collapse collapse<?php echo $pase_establecimiento_add->MultiPages->PageStyle("1") ?>" id="tab_pase_establecimiento1">
			<div class="panel-body">
<div>
<?php if ($pase_establecimiento->Nombre_Titular->Visible) { // Nombre_Titular ?>
	<div id="r_Nombre_Titular" class="form-group">
		<label id="elh_pase_establecimiento_Nombre_Titular" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Nombre_Titular->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Nombre_Titular->CellAttributes() ?>>
<span id="el_pase_establecimiento_Nombre_Titular">
<?php $pase_establecimiento->Nombre_Titular->EditAttrs["onchange"] = "ew_AutoFill(this); " . @$pase_establecimiento->Nombre_Titular->EditAttrs["onchange"]; ?>
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_Nombre_Titular"><?php echo (strval($pase_establecimiento->Nombre_Titular->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $pase_establecimiento->Nombre_Titular->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($pase_establecimiento->Nombre_Titular->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_Nombre_Titular',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="pase_establecimiento" data-field="x_Nombre_Titular" data-page="1" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $pase_establecimiento->Nombre_Titular->DisplayValueSeparatorAttribute() ?>" name="x_Nombre_Titular" id="x_Nombre_Titular" value="<?php echo $pase_establecimiento->Nombre_Titular->CurrentValue ?>"<?php echo $pase_establecimiento->Nombre_Titular->EditAttributes() ?>>
<?php if (AllowAdd(CurrentProjectID() . "personas")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $pase_establecimiento->Nombre_Titular->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_Nombre_Titular',url:'personasaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_Nombre_Titular"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $pase_establecimiento->Nombre_Titular->FldCaption() ?></span></button>
<?php } ?>
<input type="hidden" name="s_x_Nombre_Titular" id="s_x_Nombre_Titular" value="<?php echo $pase_establecimiento->Nombre_Titular->LookupFilterQuery() ?>">
<input type="hidden" name="ln_x_Nombre_Titular" id="ln_x_Nombre_Titular" value="x_Serie_Equipo,x_Dni_Titular,x_Cuil_Titular">
</span>
<?php echo $pase_establecimiento->Nombre_Titular->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pase_establecimiento->Dni_Titular->Visible) { // Dni_Titular ?>
	<div id="r_Dni_Titular" class="form-group">
		<label id="elh_pase_establecimiento_Dni_Titular" for="x_Dni_Titular" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Dni_Titular->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Dni_Titular->CellAttributes() ?>>
<span id="el_pase_establecimiento_Dni_Titular">
<input type="text" data-table="pase_establecimiento" data-field="x_Dni_Titular" data-page="1" name="x_Dni_Titular" id="x_Dni_Titular" size="30" placeholder="<?php echo ew_HtmlEncode($pase_establecimiento->Dni_Titular->getPlaceHolder()) ?>" value="<?php echo $pase_establecimiento->Dni_Titular->EditValue ?>"<?php echo $pase_establecimiento->Dni_Titular->EditAttributes() ?>>
</span>
<?php echo $pase_establecimiento->Dni_Titular->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pase_establecimiento->Cuil_Titular->Visible) { // Cuil_Titular ?>
	<div id="r_Cuil_Titular" class="form-group">
		<label id="elh_pase_establecimiento_Cuil_Titular" for="x_Cuil_Titular" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Cuil_Titular->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Cuil_Titular->CellAttributes() ?>>
<span id="el_pase_establecimiento_Cuil_Titular">
<input type="text" data-table="pase_establecimiento" data-field="x_Cuil_Titular" data-page="1" name="x_Cuil_Titular" id="x_Cuil_Titular" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($pase_establecimiento->Cuil_Titular->getPlaceHolder()) ?>" value="<?php echo $pase_establecimiento->Cuil_Titular->EditValue ?>"<?php echo $pase_establecimiento->Cuil_Titular->EditAttributes() ?>>
</span>
<?php echo $pase_establecimiento->Cuil_Titular->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
			</div>
		</div>
	</div>
	<div class="panel panel-default<?php echo $pase_establecimiento_add->MultiPages->PageStyle("2") ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a class="panel-toggle" data-toggle="collapse" data-parent="#pase_establecimiento_add" href="#tab_pase_establecimiento2"><?php echo $pase_establecimiento->PageCaption(2) ?></a>
			</h4>
		</div>
		<div class="panel-collapse collapse<?php echo $pase_establecimiento_add->MultiPages->PageStyle("2") ?>" id="tab_pase_establecimiento2">
			<div class="panel-body">
<div>
<?php if ($pase_establecimiento->Nombre_Tutor->Visible) { // Nombre_Tutor ?>
	<div id="r_Nombre_Tutor" class="form-group">
		<label id="elh_pase_establecimiento_Nombre_Tutor" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Nombre_Tutor->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Nombre_Tutor->CellAttributes() ?>>
<span id="el_pase_establecimiento_Nombre_Tutor">
<?php $pase_establecimiento->Nombre_Tutor->EditAttrs["onchange"] = "ew_AutoFill(this); " . @$pase_establecimiento->Nombre_Tutor->EditAttrs["onchange"]; ?>
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_Nombre_Tutor"><?php echo (strval($pase_establecimiento->Nombre_Tutor->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $pase_establecimiento->Nombre_Tutor->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($pase_establecimiento->Nombre_Tutor->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_Nombre_Tutor',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="pase_establecimiento" data-field="x_Nombre_Tutor" data-page="2" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $pase_establecimiento->Nombre_Tutor->DisplayValueSeparatorAttribute() ?>" name="x_Nombre_Tutor" id="x_Nombre_Tutor" value="<?php echo $pase_establecimiento->Nombre_Tutor->CurrentValue ?>"<?php echo $pase_establecimiento->Nombre_Tutor->EditAttributes() ?>>
<?php if (AllowAdd(CurrentProjectID() . "tutores")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $pase_establecimiento->Nombre_Tutor->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_Nombre_Tutor',url:'tutoresaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_Nombre_Tutor"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $pase_establecimiento->Nombre_Tutor->FldCaption() ?></span></button>
<?php } ?>
<input type="hidden" name="s_x_Nombre_Tutor" id="s_x_Nombre_Tutor" value="<?php echo $pase_establecimiento->Nombre_Tutor->LookupFilterQuery() ?>">
<input type="hidden" name="ln_x_Nombre_Tutor" id="ln_x_Nombre_Tutor" value="x_DniTutor,x_Domicilio,x_Tel_Tutor,x_CelTutor">
</span>
<?php echo $pase_establecimiento->Nombre_Tutor->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pase_establecimiento->DniTutor->Visible) { // DniTutor ?>
	<div id="r_DniTutor" class="form-group">
		<label id="elh_pase_establecimiento_DniTutor" for="x_DniTutor" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->DniTutor->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->DniTutor->CellAttributes() ?>>
<span id="el_pase_establecimiento_DniTutor">
<input type="text" data-table="pase_establecimiento" data-field="x_DniTutor" data-page="2" name="x_DniTutor" id="x_DniTutor" size="30" placeholder="<?php echo ew_HtmlEncode($pase_establecimiento->DniTutor->getPlaceHolder()) ?>" value="<?php echo $pase_establecimiento->DniTutor->EditValue ?>"<?php echo $pase_establecimiento->DniTutor->EditAttributes() ?>>
</span>
<?php echo $pase_establecimiento->DniTutor->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pase_establecimiento->Domicilio->Visible) { // Domicilio ?>
	<div id="r_Domicilio" class="form-group">
		<label id="elh_pase_establecimiento_Domicilio" for="x_Domicilio" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Domicilio->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Domicilio->CellAttributes() ?>>
<span id="el_pase_establecimiento_Domicilio">
<input type="text" data-table="pase_establecimiento" data-field="x_Domicilio" data-page="2" name="x_Domicilio" id="x_Domicilio" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($pase_establecimiento->Domicilio->getPlaceHolder()) ?>" value="<?php echo $pase_establecimiento->Domicilio->EditValue ?>"<?php echo $pase_establecimiento->Domicilio->EditAttributes() ?>>
</span>
<?php echo $pase_establecimiento->Domicilio->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pase_establecimiento->Tel_Tutor->Visible) { // Tel_Tutor ?>
	<div id="r_Tel_Tutor" class="form-group">
		<label id="elh_pase_establecimiento_Tel_Tutor" for="x_Tel_Tutor" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Tel_Tutor->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Tel_Tutor->CellAttributes() ?>>
<span id="el_pase_establecimiento_Tel_Tutor">
<input type="text" data-table="pase_establecimiento" data-field="x_Tel_Tutor" data-page="2" name="x_Tel_Tutor" id="x_Tel_Tutor" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($pase_establecimiento->Tel_Tutor->getPlaceHolder()) ?>" value="<?php echo $pase_establecimiento->Tel_Tutor->EditValue ?>"<?php echo $pase_establecimiento->Tel_Tutor->EditAttributes() ?>>
</span>
<?php echo $pase_establecimiento->Tel_Tutor->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pase_establecimiento->CelTutor->Visible) { // CelTutor ?>
	<div id="r_CelTutor" class="form-group">
		<label id="elh_pase_establecimiento_CelTutor" for="x_CelTutor" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->CelTutor->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->CelTutor->CellAttributes() ?>>
<span id="el_pase_establecimiento_CelTutor">
<input type="text" data-table="pase_establecimiento" data-field="x_CelTutor" data-page="2" name="x_CelTutor" id="x_CelTutor" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($pase_establecimiento->CelTutor->getPlaceHolder()) ?>" value="<?php echo $pase_establecimiento->CelTutor->EditValue ?>"<?php echo $pase_establecimiento->CelTutor->EditAttributes() ?>>
</span>
<?php echo $pase_establecimiento->CelTutor->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
			</div>
		</div>
	</div>
	<div class="panel panel-default<?php echo $pase_establecimiento_add->MultiPages->PageStyle("3") ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a class="panel-toggle" data-toggle="collapse" data-parent="#pase_establecimiento_add" href="#tab_pase_establecimiento3"><?php echo $pase_establecimiento->PageCaption(3) ?></a>
			</h4>
		</div>
		<div class="panel-collapse collapse<?php echo $pase_establecimiento_add->MultiPages->PageStyle("3") ?>" id="tab_pase_establecimiento3">
			<div class="panel-body">
<div>
<?php if ($pase_establecimiento->Serie_Equipo->Visible) { // Serie_Equipo ?>
	<div id="r_Serie_Equipo" class="form-group">
		<label id="elh_pase_establecimiento_Serie_Equipo" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Serie_Equipo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Serie_Equipo->CellAttributes() ?>>
<span id="el_pase_establecimiento_Serie_Equipo">
<?php $pase_establecimiento->Serie_Equipo->EditAttrs["onchange"] = "ew_AutoFill(this); " . @$pase_establecimiento->Serie_Equipo->EditAttrs["onchange"]; ?>
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_Serie_Equipo"><?php echo (strval($pase_establecimiento->Serie_Equipo->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $pase_establecimiento->Serie_Equipo->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($pase_establecimiento->Serie_Equipo->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_Serie_Equipo',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="pase_establecimiento" data-field="x_Serie_Equipo" data-page="3" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $pase_establecimiento->Serie_Equipo->DisplayValueSeparatorAttribute() ?>" name="x_Serie_Equipo" id="x_Serie_Equipo" value="<?php echo $pase_establecimiento->Serie_Equipo->CurrentValue ?>"<?php echo $pase_establecimiento->Serie_Equipo->EditAttributes() ?>>
<?php if (AllowAdd(CurrentProjectID() . "equipos")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $pase_establecimiento->Serie_Equipo->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_Serie_Equipo',url:'equiposaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_Serie_Equipo"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $pase_establecimiento->Serie_Equipo->FldCaption() ?></span></button>
<?php } ?>
<input type="hidden" name="s_x_Serie_Equipo" id="s_x_Serie_Equipo" value="<?php echo $pase_establecimiento->Serie_Equipo->LookupFilterQuery() ?>">
<input type="hidden" name="ln_x_Serie_Equipo" id="ln_x_Serie_Equipo" value="x_Id_Hardware,x_SN,x_Modelo_Net">
</span>
<?php echo $pase_establecimiento->Serie_Equipo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pase_establecimiento->Id_Hardware->Visible) { // Id_Hardware ?>
	<div id="r_Id_Hardware" class="form-group">
		<label id="elh_pase_establecimiento_Id_Hardware" for="x_Id_Hardware" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Id_Hardware->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Id_Hardware->CellAttributes() ?>>
<span id="el_pase_establecimiento_Id_Hardware">
<input type="text" data-table="pase_establecimiento" data-field="x_Id_Hardware" data-page="3" name="x_Id_Hardware" id="x_Id_Hardware" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($pase_establecimiento->Id_Hardware->getPlaceHolder()) ?>" value="<?php echo $pase_establecimiento->Id_Hardware->EditValue ?>"<?php echo $pase_establecimiento->Id_Hardware->EditAttributes() ?>>
</span>
<?php echo $pase_establecimiento->Id_Hardware->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pase_establecimiento->SN->Visible) { // SN ?>
	<div id="r_SN" class="form-group">
		<label id="elh_pase_establecimiento_SN" for="x_SN" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->SN->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->SN->CellAttributes() ?>>
<span id="el_pase_establecimiento_SN">
<input type="text" data-table="pase_establecimiento" data-field="x_SN" data-page="3" name="x_SN" id="x_SN" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($pase_establecimiento->SN->getPlaceHolder()) ?>" value="<?php echo $pase_establecimiento->SN->EditValue ?>"<?php echo $pase_establecimiento->SN->EditAttributes() ?>>
</span>
<?php echo $pase_establecimiento->SN->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pase_establecimiento->Modelo_Net->Visible) { // Modelo_Net ?>
	<div id="r_Modelo_Net" class="form-group">
		<label id="elh_pase_establecimiento_Modelo_Net" for="x_Modelo_Net" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Modelo_Net->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Modelo_Net->CellAttributes() ?>>
<span id="el_pase_establecimiento_Modelo_Net">
<select data-table="pase_establecimiento" data-field="x_Modelo_Net" data-page="3" data-value-separator="<?php echo $pase_establecimiento->Modelo_Net->DisplayValueSeparatorAttribute() ?>" id="x_Modelo_Net" name="x_Modelo_Net"<?php echo $pase_establecimiento->Modelo_Net->EditAttributes() ?>>
<?php echo $pase_establecimiento->Modelo_Net->SelectOptionListHtml("x_Modelo_Net") ?>
</select>
<input type="hidden" name="s_x_Modelo_Net" id="s_x_Modelo_Net" value="<?php echo $pase_establecimiento->Modelo_Net->LookupFilterQuery() ?>">
</span>
<?php echo $pase_establecimiento->Modelo_Net->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pase_establecimiento->Marca_Arranque->Visible) { // Marca_Arranque ?>
	<div id="r_Marca_Arranque" class="form-group">
		<label id="elh_pase_establecimiento_Marca_Arranque" for="x_Marca_Arranque" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Marca_Arranque->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Marca_Arranque->CellAttributes() ?>>
<span id="el_pase_establecimiento_Marca_Arranque">
<input type="text" data-table="pase_establecimiento" data-field="x_Marca_Arranque" data-page="3" name="x_Marca_Arranque" id="x_Marca_Arranque" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($pase_establecimiento->Marca_Arranque->getPlaceHolder()) ?>" value="<?php echo $pase_establecimiento->Marca_Arranque->EditValue ?>"<?php echo $pase_establecimiento->Marca_Arranque->EditAttributes() ?>>
</span>
<?php echo $pase_establecimiento->Marca_Arranque->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
			</div>
		</div>
	</div>
	<div class="panel panel-default<?php echo $pase_establecimiento_add->MultiPages->PageStyle("4") ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a class="panel-toggle" data-toggle="collapse" data-parent="#pase_establecimiento_add" href="#tab_pase_establecimiento4"><?php echo $pase_establecimiento->PageCaption(4) ?></a>
			</h4>
		</div>
		<div class="panel-collapse collapse<?php echo $pase_establecimiento_add->MultiPages->PageStyle("4") ?>" id="tab_pase_establecimiento4">
			<div class="panel-body">
<div>
<?php if ($pase_establecimiento->Cue_Establecimiento_Baja->Visible) { // Cue_Establecimiento_Baja ?>
	<div id="r_Cue_Establecimiento_Baja" class="form-group">
		<label id="elh_pase_establecimiento_Cue_Establecimiento_Baja" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Cue_Establecimiento_Baja->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Cue_Establecimiento_Baja->CellAttributes() ?>>
<span id="el_pase_establecimiento_Cue_Establecimiento_Baja">
<?php $pase_establecimiento->Cue_Establecimiento_Baja->EditAttrs["onchange"] = "ew_AutoFill(this); " . @$pase_establecimiento->Cue_Establecimiento_Baja->EditAttrs["onchange"]; ?>
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_Cue_Establecimiento_Baja"><?php echo (strval($pase_establecimiento->Cue_Establecimiento_Baja->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $pase_establecimiento->Cue_Establecimiento_Baja->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($pase_establecimiento->Cue_Establecimiento_Baja->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_Cue_Establecimiento_Baja',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="pase_establecimiento" data-field="x_Cue_Establecimiento_Baja" data-page="4" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $pase_establecimiento->Cue_Establecimiento_Baja->DisplayValueSeparatorAttribute() ?>" name="x_Cue_Establecimiento_Baja" id="x_Cue_Establecimiento_Baja" value="<?php echo $pase_establecimiento->Cue_Establecimiento_Baja->CurrentValue ?>"<?php echo $pase_establecimiento->Cue_Establecimiento_Baja->EditAttributes() ?>>
<?php if (AllowAdd(CurrentProjectID() . "establecimientos_educativos_pase")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $pase_establecimiento->Cue_Establecimiento_Baja->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_Cue_Establecimiento_Baja',url:'establecimientos_educativos_paseaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_Cue_Establecimiento_Baja"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $pase_establecimiento->Cue_Establecimiento_Baja->FldCaption() ?></span></button>
<?php } ?>
<input type="hidden" name="s_x_Cue_Establecimiento_Baja" id="s_x_Cue_Establecimiento_Baja" value="<?php echo $pase_establecimiento->Cue_Establecimiento_Baja->LookupFilterQuery() ?>">
<input type="hidden" name="ln_x_Cue_Establecimiento_Baja" id="ln_x_Cue_Establecimiento_Baja" value="x_Escuela_Baja,x_Directivo_Baja,x_Cuil_Directivo_Baja,x_Dpto_Esc_Baja,x_Localidad_Esc_Baja,x_Domicilio_Esc_Baja,x_Rte_Baja,x_Tel_Rte_Baja,x_Email_Rte_Baja,x_Serie_Server_Baja">
</span>
<?php echo $pase_establecimiento->Cue_Establecimiento_Baja->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pase_establecimiento->Escuela_Baja->Visible) { // Escuela_Baja ?>
	<div id="r_Escuela_Baja" class="form-group">
		<label id="elh_pase_establecimiento_Escuela_Baja" for="x_Escuela_Baja" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Escuela_Baja->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Escuela_Baja->CellAttributes() ?>>
<span id="el_pase_establecimiento_Escuela_Baja">
<input type="text" data-table="pase_establecimiento" data-field="x_Escuela_Baja" data-page="4" name="x_Escuela_Baja" id="x_Escuela_Baja" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($pase_establecimiento->Escuela_Baja->getPlaceHolder()) ?>" value="<?php echo $pase_establecimiento->Escuela_Baja->EditValue ?>"<?php echo $pase_establecimiento->Escuela_Baja->EditAttributes() ?>>
</span>
<?php echo $pase_establecimiento->Escuela_Baja->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pase_establecimiento->Directivo_Baja->Visible) { // Directivo_Baja ?>
	<div id="r_Directivo_Baja" class="form-group">
		<label id="elh_pase_establecimiento_Directivo_Baja" for="x_Directivo_Baja" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Directivo_Baja->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Directivo_Baja->CellAttributes() ?>>
<span id="el_pase_establecimiento_Directivo_Baja">
<input type="text" data-table="pase_establecimiento" data-field="x_Directivo_Baja" data-page="4" name="x_Directivo_Baja" id="x_Directivo_Baja" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($pase_establecimiento->Directivo_Baja->getPlaceHolder()) ?>" value="<?php echo $pase_establecimiento->Directivo_Baja->EditValue ?>"<?php echo $pase_establecimiento->Directivo_Baja->EditAttributes() ?>>
</span>
<?php echo $pase_establecimiento->Directivo_Baja->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pase_establecimiento->Cuil_Directivo_Baja->Visible) { // Cuil_Directivo_Baja ?>
	<div id="r_Cuil_Directivo_Baja" class="form-group">
		<label id="elh_pase_establecimiento_Cuil_Directivo_Baja" for="x_Cuil_Directivo_Baja" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Cuil_Directivo_Baja->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Cuil_Directivo_Baja->CellAttributes() ?>>
<span id="el_pase_establecimiento_Cuil_Directivo_Baja">
<input type="text" data-table="pase_establecimiento" data-field="x_Cuil_Directivo_Baja" data-page="4" name="x_Cuil_Directivo_Baja" id="x_Cuil_Directivo_Baja" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($pase_establecimiento->Cuil_Directivo_Baja->getPlaceHolder()) ?>" value="<?php echo $pase_establecimiento->Cuil_Directivo_Baja->EditValue ?>"<?php echo $pase_establecimiento->Cuil_Directivo_Baja->EditAttributes() ?>>
</span>
<?php echo $pase_establecimiento->Cuil_Directivo_Baja->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pase_establecimiento->Dpto_Esc_Baja->Visible) { // Dpto_Esc_Baja ?>
	<div id="r_Dpto_Esc_Baja" class="form-group">
		<label id="elh_pase_establecimiento_Dpto_Esc_Baja" for="x_Dpto_Esc_Baja" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Dpto_Esc_Baja->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Dpto_Esc_Baja->CellAttributes() ?>>
<span id="el_pase_establecimiento_Dpto_Esc_Baja">
<select data-table="pase_establecimiento" data-field="x_Dpto_Esc_Baja" data-page="4" data-value-separator="<?php echo $pase_establecimiento->Dpto_Esc_Baja->DisplayValueSeparatorAttribute() ?>" id="x_Dpto_Esc_Baja" name="x_Dpto_Esc_Baja"<?php echo $pase_establecimiento->Dpto_Esc_Baja->EditAttributes() ?>>
<?php echo $pase_establecimiento->Dpto_Esc_Baja->SelectOptionListHtml("x_Dpto_Esc_Baja") ?>
</select>
<input type="hidden" name="s_x_Dpto_Esc_Baja" id="s_x_Dpto_Esc_Baja" value="<?php echo $pase_establecimiento->Dpto_Esc_Baja->LookupFilterQuery() ?>">
</span>
<?php echo $pase_establecimiento->Dpto_Esc_Baja->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pase_establecimiento->Localidad_Esc_Baja->Visible) { // Localidad_Esc_Baja ?>
	<div id="r_Localidad_Esc_Baja" class="form-group">
		<label id="elh_pase_establecimiento_Localidad_Esc_Baja" for="x_Localidad_Esc_Baja" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Localidad_Esc_Baja->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Localidad_Esc_Baja->CellAttributes() ?>>
<span id="el_pase_establecimiento_Localidad_Esc_Baja">
<select data-table="pase_establecimiento" data-field="x_Localidad_Esc_Baja" data-page="4" data-value-separator="<?php echo $pase_establecimiento->Localidad_Esc_Baja->DisplayValueSeparatorAttribute() ?>" id="x_Localidad_Esc_Baja" name="x_Localidad_Esc_Baja"<?php echo $pase_establecimiento->Localidad_Esc_Baja->EditAttributes() ?>>
<?php echo $pase_establecimiento->Localidad_Esc_Baja->SelectOptionListHtml("x_Localidad_Esc_Baja") ?>
</select>
<input type="hidden" name="s_x_Localidad_Esc_Baja" id="s_x_Localidad_Esc_Baja" value="<?php echo $pase_establecimiento->Localidad_Esc_Baja->LookupFilterQuery() ?>">
</span>
<?php echo $pase_establecimiento->Localidad_Esc_Baja->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pase_establecimiento->Domicilio_Esc_Baja->Visible) { // Domicilio_Esc_Baja ?>
	<div id="r_Domicilio_Esc_Baja" class="form-group">
		<label id="elh_pase_establecimiento_Domicilio_Esc_Baja" for="x_Domicilio_Esc_Baja" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Domicilio_Esc_Baja->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Domicilio_Esc_Baja->CellAttributes() ?>>
<span id="el_pase_establecimiento_Domicilio_Esc_Baja">
<input type="text" data-table="pase_establecimiento" data-field="x_Domicilio_Esc_Baja" data-page="4" name="x_Domicilio_Esc_Baja" id="x_Domicilio_Esc_Baja" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($pase_establecimiento->Domicilio_Esc_Baja->getPlaceHolder()) ?>" value="<?php echo $pase_establecimiento->Domicilio_Esc_Baja->EditValue ?>"<?php echo $pase_establecimiento->Domicilio_Esc_Baja->EditAttributes() ?>>
</span>
<?php echo $pase_establecimiento->Domicilio_Esc_Baja->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pase_establecimiento->Rte_Baja->Visible) { // Rte_Baja ?>
	<div id="r_Rte_Baja" class="form-group">
		<label id="elh_pase_establecimiento_Rte_Baja" for="x_Rte_Baja" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Rte_Baja->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Rte_Baja->CellAttributes() ?>>
<span id="el_pase_establecimiento_Rte_Baja">
<input type="text" data-table="pase_establecimiento" data-field="x_Rte_Baja" data-page="4" name="x_Rte_Baja" id="x_Rte_Baja" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($pase_establecimiento->Rte_Baja->getPlaceHolder()) ?>" value="<?php echo $pase_establecimiento->Rte_Baja->EditValue ?>"<?php echo $pase_establecimiento->Rte_Baja->EditAttributes() ?>>
</span>
<?php echo $pase_establecimiento->Rte_Baja->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pase_establecimiento->Tel_Rte_Baja->Visible) { // Tel_Rte_Baja ?>
	<div id="r_Tel_Rte_Baja" class="form-group">
		<label id="elh_pase_establecimiento_Tel_Rte_Baja" for="x_Tel_Rte_Baja" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Tel_Rte_Baja->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Tel_Rte_Baja->CellAttributes() ?>>
<span id="el_pase_establecimiento_Tel_Rte_Baja">
<input type="text" data-table="pase_establecimiento" data-field="x_Tel_Rte_Baja" data-page="4" name="x_Tel_Rte_Baja" id="x_Tel_Rte_Baja" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($pase_establecimiento->Tel_Rte_Baja->getPlaceHolder()) ?>" value="<?php echo $pase_establecimiento->Tel_Rte_Baja->EditValue ?>"<?php echo $pase_establecimiento->Tel_Rte_Baja->EditAttributes() ?>>
</span>
<?php echo $pase_establecimiento->Tel_Rte_Baja->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pase_establecimiento->Email_Rte_Baja->Visible) { // Email_Rte_Baja ?>
	<div id="r_Email_Rte_Baja" class="form-group">
		<label id="elh_pase_establecimiento_Email_Rte_Baja" for="x_Email_Rte_Baja" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Email_Rte_Baja->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Email_Rte_Baja->CellAttributes() ?>>
<span id="el_pase_establecimiento_Email_Rte_Baja">
<input type="text" data-table="pase_establecimiento" data-field="x_Email_Rte_Baja" data-page="4" name="x_Email_Rte_Baja" id="x_Email_Rte_Baja" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($pase_establecimiento->Email_Rte_Baja->getPlaceHolder()) ?>" value="<?php echo $pase_establecimiento->Email_Rte_Baja->EditValue ?>"<?php echo $pase_establecimiento->Email_Rte_Baja->EditAttributes() ?>>
</span>
<?php echo $pase_establecimiento->Email_Rte_Baja->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pase_establecimiento->Serie_Server_Baja->Visible) { // Serie_Server_Baja ?>
	<div id="r_Serie_Server_Baja" class="form-group">
		<label id="elh_pase_establecimiento_Serie_Server_Baja" for="x_Serie_Server_Baja" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Serie_Server_Baja->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Serie_Server_Baja->CellAttributes() ?>>
<span id="el_pase_establecimiento_Serie_Server_Baja">
<input type="text" data-table="pase_establecimiento" data-field="x_Serie_Server_Baja" data-page="4" name="x_Serie_Server_Baja" id="x_Serie_Server_Baja" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($pase_establecimiento->Serie_Server_Baja->getPlaceHolder()) ?>" value="<?php echo $pase_establecimiento->Serie_Server_Baja->EditValue ?>"<?php echo $pase_establecimiento->Serie_Server_Baja->EditAttributes() ?>>
</span>
<?php echo $pase_establecimiento->Serie_Server_Baja->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
			</div>
		</div>
	</div>
	<div class="panel panel-default<?php echo $pase_establecimiento_add->MultiPages->PageStyle("5") ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a class="panel-toggle" data-toggle="collapse" data-parent="#pase_establecimiento_add" href="#tab_pase_establecimiento5"><?php echo $pase_establecimiento->PageCaption(5) ?></a>
			</h4>
		</div>
		<div class="panel-collapse collapse<?php echo $pase_establecimiento_add->MultiPages->PageStyle("5") ?>" id="tab_pase_establecimiento5">
			<div class="panel-body">
<div>
<?php if ($pase_establecimiento->Cue_Establecimiento_Alta->Visible) { // Cue_Establecimiento_Alta ?>
	<div id="r_Cue_Establecimiento_Alta" class="form-group">
		<label id="elh_pase_establecimiento_Cue_Establecimiento_Alta" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Cue_Establecimiento_Alta->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Cue_Establecimiento_Alta->CellAttributes() ?>>
<span id="el_pase_establecimiento_Cue_Establecimiento_Alta">
<?php $pase_establecimiento->Cue_Establecimiento_Alta->EditAttrs["onchange"] = "ew_AutoFill(this); " . @$pase_establecimiento->Cue_Establecimiento_Alta->EditAttrs["onchange"]; ?>
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_Cue_Establecimiento_Alta"><?php echo (strval($pase_establecimiento->Cue_Establecimiento_Alta->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $pase_establecimiento->Cue_Establecimiento_Alta->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($pase_establecimiento->Cue_Establecimiento_Alta->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_Cue_Establecimiento_Alta',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="pase_establecimiento" data-field="x_Cue_Establecimiento_Alta" data-page="5" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $pase_establecimiento->Cue_Establecimiento_Alta->DisplayValueSeparatorAttribute() ?>" name="x_Cue_Establecimiento_Alta" id="x_Cue_Establecimiento_Alta" value="<?php echo $pase_establecimiento->Cue_Establecimiento_Alta->CurrentValue ?>"<?php echo $pase_establecimiento->Cue_Establecimiento_Alta->EditAttributes() ?>>
<?php if (AllowAdd(CurrentProjectID() . "establecimientos_educativos_pase")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $pase_establecimiento->Cue_Establecimiento_Alta->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_Cue_Establecimiento_Alta',url:'establecimientos_educativos_paseaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_Cue_Establecimiento_Alta"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $pase_establecimiento->Cue_Establecimiento_Alta->FldCaption() ?></span></button>
<?php } ?>
<input type="hidden" name="s_x_Cue_Establecimiento_Alta" id="s_x_Cue_Establecimiento_Alta" value="<?php echo $pase_establecimiento->Cue_Establecimiento_Alta->LookupFilterQuery() ?>">
<input type="hidden" name="ln_x_Cue_Establecimiento_Alta" id="ln_x_Cue_Establecimiento_Alta" value="x_Escuela_Alta,x_Directivo_Alta,x_Cuil_Directivo_Alta,x_Dpto_Esc_alta,x_Localidad_Esc_Alta,x_Domicilio_Esc_Alta,x_Rte_Alta,x_Email_Rte_Alta,x_Serie_Server_Alta">
</span>
<?php echo $pase_establecimiento->Cue_Establecimiento_Alta->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pase_establecimiento->Escuela_Alta->Visible) { // Escuela_Alta ?>
	<div id="r_Escuela_Alta" class="form-group">
		<label id="elh_pase_establecimiento_Escuela_Alta" for="x_Escuela_Alta" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Escuela_Alta->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Escuela_Alta->CellAttributes() ?>>
<span id="el_pase_establecimiento_Escuela_Alta">
<input type="text" data-table="pase_establecimiento" data-field="x_Escuela_Alta" data-page="5" name="x_Escuela_Alta" id="x_Escuela_Alta" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($pase_establecimiento->Escuela_Alta->getPlaceHolder()) ?>" value="<?php echo $pase_establecimiento->Escuela_Alta->EditValue ?>"<?php echo $pase_establecimiento->Escuela_Alta->EditAttributes() ?>>
</span>
<?php echo $pase_establecimiento->Escuela_Alta->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pase_establecimiento->Directivo_Alta->Visible) { // Directivo_Alta ?>
	<div id="r_Directivo_Alta" class="form-group">
		<label id="elh_pase_establecimiento_Directivo_Alta" for="x_Directivo_Alta" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Directivo_Alta->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Directivo_Alta->CellAttributes() ?>>
<span id="el_pase_establecimiento_Directivo_Alta">
<input type="text" data-table="pase_establecimiento" data-field="x_Directivo_Alta" data-page="5" name="x_Directivo_Alta" id="x_Directivo_Alta" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($pase_establecimiento->Directivo_Alta->getPlaceHolder()) ?>" value="<?php echo $pase_establecimiento->Directivo_Alta->EditValue ?>"<?php echo $pase_establecimiento->Directivo_Alta->EditAttributes() ?>>
</span>
<?php echo $pase_establecimiento->Directivo_Alta->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pase_establecimiento->Cuil_Directivo_Alta->Visible) { // Cuil_Directivo_Alta ?>
	<div id="r_Cuil_Directivo_Alta" class="form-group">
		<label id="elh_pase_establecimiento_Cuil_Directivo_Alta" for="x_Cuil_Directivo_Alta" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Cuil_Directivo_Alta->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Cuil_Directivo_Alta->CellAttributes() ?>>
<span id="el_pase_establecimiento_Cuil_Directivo_Alta">
<input type="text" data-table="pase_establecimiento" data-field="x_Cuil_Directivo_Alta" data-page="5" name="x_Cuil_Directivo_Alta" id="x_Cuil_Directivo_Alta" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($pase_establecimiento->Cuil_Directivo_Alta->getPlaceHolder()) ?>" value="<?php echo $pase_establecimiento->Cuil_Directivo_Alta->EditValue ?>"<?php echo $pase_establecimiento->Cuil_Directivo_Alta->EditAttributes() ?>>
</span>
<?php echo $pase_establecimiento->Cuil_Directivo_Alta->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pase_establecimiento->Dpto_Esc_alta->Visible) { // Dpto_Esc_alta ?>
	<div id="r_Dpto_Esc_alta" class="form-group">
		<label id="elh_pase_establecimiento_Dpto_Esc_alta" for="x_Dpto_Esc_alta" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Dpto_Esc_alta->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Dpto_Esc_alta->CellAttributes() ?>>
<span id="el_pase_establecimiento_Dpto_Esc_alta">
<select data-table="pase_establecimiento" data-field="x_Dpto_Esc_alta" data-page="5" data-value-separator="<?php echo $pase_establecimiento->Dpto_Esc_alta->DisplayValueSeparatorAttribute() ?>" id="x_Dpto_Esc_alta" name="x_Dpto_Esc_alta"<?php echo $pase_establecimiento->Dpto_Esc_alta->EditAttributes() ?>>
<?php echo $pase_establecimiento->Dpto_Esc_alta->SelectOptionListHtml("x_Dpto_Esc_alta") ?>
</select>
<input type="hidden" name="s_x_Dpto_Esc_alta" id="s_x_Dpto_Esc_alta" value="<?php echo $pase_establecimiento->Dpto_Esc_alta->LookupFilterQuery() ?>">
</span>
<?php echo $pase_establecimiento->Dpto_Esc_alta->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pase_establecimiento->Localidad_Esc_Alta->Visible) { // Localidad_Esc_Alta ?>
	<div id="r_Localidad_Esc_Alta" class="form-group">
		<label id="elh_pase_establecimiento_Localidad_Esc_Alta" for="x_Localidad_Esc_Alta" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Localidad_Esc_Alta->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Localidad_Esc_Alta->CellAttributes() ?>>
<span id="el_pase_establecimiento_Localidad_Esc_Alta">
<select data-table="pase_establecimiento" data-field="x_Localidad_Esc_Alta" data-page="5" data-value-separator="<?php echo $pase_establecimiento->Localidad_Esc_Alta->DisplayValueSeparatorAttribute() ?>" id="x_Localidad_Esc_Alta" name="x_Localidad_Esc_Alta"<?php echo $pase_establecimiento->Localidad_Esc_Alta->EditAttributes() ?>>
<?php echo $pase_establecimiento->Localidad_Esc_Alta->SelectOptionListHtml("x_Localidad_Esc_Alta") ?>
</select>
<input type="hidden" name="s_x_Localidad_Esc_Alta" id="s_x_Localidad_Esc_Alta" value="<?php echo $pase_establecimiento->Localidad_Esc_Alta->LookupFilterQuery() ?>">
</span>
<?php echo $pase_establecimiento->Localidad_Esc_Alta->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pase_establecimiento->Domicilio_Esc_Alta->Visible) { // Domicilio_Esc_Alta ?>
	<div id="r_Domicilio_Esc_Alta" class="form-group">
		<label id="elh_pase_establecimiento_Domicilio_Esc_Alta" for="x_Domicilio_Esc_Alta" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Domicilio_Esc_Alta->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Domicilio_Esc_Alta->CellAttributes() ?>>
<span id="el_pase_establecimiento_Domicilio_Esc_Alta">
<input type="text" data-table="pase_establecimiento" data-field="x_Domicilio_Esc_Alta" data-page="5" name="x_Domicilio_Esc_Alta" id="x_Domicilio_Esc_Alta" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($pase_establecimiento->Domicilio_Esc_Alta->getPlaceHolder()) ?>" value="<?php echo $pase_establecimiento->Domicilio_Esc_Alta->EditValue ?>"<?php echo $pase_establecimiento->Domicilio_Esc_Alta->EditAttributes() ?>>
</span>
<?php echo $pase_establecimiento->Domicilio_Esc_Alta->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pase_establecimiento->Rte_Alta->Visible) { // Rte_Alta ?>
	<div id="r_Rte_Alta" class="form-group">
		<label id="elh_pase_establecimiento_Rte_Alta" for="x_Rte_Alta" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Rte_Alta->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Rte_Alta->CellAttributes() ?>>
<span id="el_pase_establecimiento_Rte_Alta">
<input type="text" data-table="pase_establecimiento" data-field="x_Rte_Alta" data-page="5" name="x_Rte_Alta" id="x_Rte_Alta" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($pase_establecimiento->Rte_Alta->getPlaceHolder()) ?>" value="<?php echo $pase_establecimiento->Rte_Alta->EditValue ?>"<?php echo $pase_establecimiento->Rte_Alta->EditAttributes() ?>>
</span>
<?php echo $pase_establecimiento->Rte_Alta->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pase_establecimiento->Tel_Rte_Alta->Visible) { // Tel_Rte_Alta ?>
	<div id="r_Tel_Rte_Alta" class="form-group">
		<label id="elh_pase_establecimiento_Tel_Rte_Alta" for="x_Tel_Rte_Alta" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Tel_Rte_Alta->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Tel_Rte_Alta->CellAttributes() ?>>
<span id="el_pase_establecimiento_Tel_Rte_Alta">
<input type="text" data-table="pase_establecimiento" data-field="x_Tel_Rte_Alta" data-page="5" name="x_Tel_Rte_Alta" id="x_Tel_Rte_Alta" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($pase_establecimiento->Tel_Rte_Alta->getPlaceHolder()) ?>" value="<?php echo $pase_establecimiento->Tel_Rte_Alta->EditValue ?>"<?php echo $pase_establecimiento->Tel_Rte_Alta->EditAttributes() ?>>
</span>
<?php echo $pase_establecimiento->Tel_Rte_Alta->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pase_establecimiento->Email_Rte_Alta->Visible) { // Email_Rte_Alta ?>
	<div id="r_Email_Rte_Alta" class="form-group">
		<label id="elh_pase_establecimiento_Email_Rte_Alta" for="x_Email_Rte_Alta" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Email_Rte_Alta->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Email_Rte_Alta->CellAttributes() ?>>
<span id="el_pase_establecimiento_Email_Rte_Alta">
<input type="text" data-table="pase_establecimiento" data-field="x_Email_Rte_Alta" data-page="5" name="x_Email_Rte_Alta" id="x_Email_Rte_Alta" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($pase_establecimiento->Email_Rte_Alta->getPlaceHolder()) ?>" value="<?php echo $pase_establecimiento->Email_Rte_Alta->EditValue ?>"<?php echo $pase_establecimiento->Email_Rte_Alta->EditAttributes() ?>>
</span>
<?php echo $pase_establecimiento->Email_Rte_Alta->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pase_establecimiento->Serie_Server_Alta->Visible) { // Serie_Server_Alta ?>
	<div id="r_Serie_Server_Alta" class="form-group">
		<label id="elh_pase_establecimiento_Serie_Server_Alta" for="x_Serie_Server_Alta" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Serie_Server_Alta->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Serie_Server_Alta->CellAttributes() ?>>
<span id="el_pase_establecimiento_Serie_Server_Alta">
<input type="text" data-table="pase_establecimiento" data-field="x_Serie_Server_Alta" data-page="5" name="x_Serie_Server_Alta" id="x_Serie_Server_Alta" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($pase_establecimiento->Serie_Server_Alta->getPlaceHolder()) ?>" value="<?php echo $pase_establecimiento->Serie_Server_Alta->EditValue ?>"<?php echo $pase_establecimiento->Serie_Server_Alta->EditAttributes() ?>>
</span>
<?php echo $pase_establecimiento->Serie_Server_Alta->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
			</div>
		</div>
	</div>
	<div class="panel panel-default<?php echo $pase_establecimiento_add->MultiPages->PageStyle("6") ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a class="panel-toggle" data-toggle="collapse" data-parent="#pase_establecimiento_add" href="#tab_pase_establecimiento6"><?php echo $pase_establecimiento->PageCaption(6) ?></a>
			</h4>
		</div>
		<div class="panel-collapse collapse<?php echo $pase_establecimiento_add->MultiPages->PageStyle("6") ?>" id="tab_pase_establecimiento6">
			<div class="panel-body">
<div>
<?php if ($pase_establecimiento->Fecha_Pase->Visible) { // Fecha_Pase ?>
	<div id="r_Fecha_Pase" class="form-group">
		<label id="elh_pase_establecimiento_Fecha_Pase" for="x_Fecha_Pase" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Fecha_Pase->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Fecha_Pase->CellAttributes() ?>>
<span id="el_pase_establecimiento_Fecha_Pase">
<input type="text" data-table="pase_establecimiento" data-field="x_Fecha_Pase" data-page="6" data-format="7" name="x_Fecha_Pase" id="x_Fecha_Pase" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($pase_establecimiento->Fecha_Pase->getPlaceHolder()) ?>" value="<?php echo $pase_establecimiento->Fecha_Pase->EditValue ?>"<?php echo $pase_establecimiento->Fecha_Pase->EditAttributes() ?>>
<?php if (!$pase_establecimiento->Fecha_Pase->ReadOnly && !$pase_establecimiento->Fecha_Pase->Disabled && !isset($pase_establecimiento->Fecha_Pase->EditAttrs["readonly"]) && !isset($pase_establecimiento->Fecha_Pase->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fpase_establecimientoadd", "x_Fecha_Pase", 7);
</script>
<?php } ?>
</span>
<?php echo $pase_establecimiento->Fecha_Pase->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pase_establecimiento->Id_Estado_Pase->Visible) { // Id_Estado_Pase ?>
	<div id="r_Id_Estado_Pase" class="form-group">
		<label id="elh_pase_establecimiento_Id_Estado_Pase" for="x_Id_Estado_Pase" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Id_Estado_Pase->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Id_Estado_Pase->CellAttributes() ?>>
<span id="el_pase_establecimiento_Id_Estado_Pase">
<select data-table="pase_establecimiento" data-field="x_Id_Estado_Pase" data-page="6" data-value-separator="<?php echo $pase_establecimiento->Id_Estado_Pase->DisplayValueSeparatorAttribute() ?>" id="x_Id_Estado_Pase" name="x_Id_Estado_Pase"<?php echo $pase_establecimiento->Id_Estado_Pase->EditAttributes() ?>>
<?php echo $pase_establecimiento->Id_Estado_Pase->SelectOptionListHtml("x_Id_Estado_Pase") ?>
</select>
<input type="hidden" name="s_x_Id_Estado_Pase" id="s_x_Id_Estado_Pase" value="<?php echo $pase_establecimiento->Id_Estado_Pase->LookupFilterQuery() ?>">
</span>
<?php echo $pase_establecimiento->Id_Estado_Pase->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pase_establecimiento->Ruta_Archivo->Visible) { // Ruta_Archivo ?>
	<div id="r_Ruta_Archivo" class="form-group">
		<label id="elh_pase_establecimiento_Ruta_Archivo" class="col-sm-2 control-label ewLabel"><?php echo $pase_establecimiento->Ruta_Archivo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pase_establecimiento->Ruta_Archivo->CellAttributes() ?>>
<span id="el_pase_establecimiento_Ruta_Archivo">
<div id="fd_x_Ruta_Archivo">
<span title="<?php echo $pase_establecimiento->Ruta_Archivo->FldTitle() ? $pase_establecimiento->Ruta_Archivo->FldTitle() : $Language->Phrase("ChooseFiles") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($pase_establecimiento->Ruta_Archivo->ReadOnly || $pase_establecimiento->Ruta_Archivo->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="pase_establecimiento" data-field="x_Ruta_Archivo" data-page="6" name="x_Ruta_Archivo" id="x_Ruta_Archivo" multiple="multiple"<?php echo $pase_establecimiento->Ruta_Archivo->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_Ruta_Archivo" id= "fn_x_Ruta_Archivo" value="<?php echo $pase_establecimiento->Ruta_Archivo->Upload->FileName ?>">
<input type="hidden" name="fa_x_Ruta_Archivo" id= "fa_x_Ruta_Archivo" value="0">
<input type="hidden" name="fs_x_Ruta_Archivo" id= "fs_x_Ruta_Archivo" value="500">
<input type="hidden" name="fx_x_Ruta_Archivo" id= "fx_x_Ruta_Archivo" value="<?php echo $pase_establecimiento->Ruta_Archivo->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_Ruta_Archivo" id= "fm_x_Ruta_Archivo" value="<?php echo $pase_establecimiento->Ruta_Archivo->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x_Ruta_Archivo" id= "fc_x_Ruta_Archivo" value="<?php echo $pase_establecimiento->Ruta_Archivo->UploadMaxFileCount ?>">
</div>
<table id="ft_x_Ruta_Archivo" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $pase_establecimiento->Ruta_Archivo->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
			</div>
		</div>
	</div>
</div>
</div>
<?php if (!$pase_establecimiento_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $pase_establecimiento_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fpase_establecimientoadd.Init();
</script>
<?php
$pase_establecimiento_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$pase_establecimiento_add->Page_Terminate();
?>
