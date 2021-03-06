<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "estado_actual_legajo_personainfo.php" ?>
<?php include_once "personasinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$estado_actual_legajo_persona_list = NULL; // Initialize page object first

class cestado_actual_legajo_persona_list extends cestado_actual_legajo_persona {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{9FD9BA28-0339-4B41-9A45-0CAE935EFE3A}";

	// Table name
	var $TableName = 'estado_actual_legajo_persona';

	// Page object name
	var $PageObjName = 'estado_actual_legajo_persona_list';

	// Grid form hidden field names
	var $FormName = 'festado_actual_legajo_personalist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;
	var $AuditTrailOnAdd = TRUE;
	var $AuditTrailOnEdit = TRUE;
	var $AuditTrailOnDelete = TRUE;
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

		// Table object (estado_actual_legajo_persona)
		if (!isset($GLOBALS["estado_actual_legajo_persona"]) || get_class($GLOBALS["estado_actual_legajo_persona"]) == "cestado_actual_legajo_persona") {
			$GLOBALS["estado_actual_legajo_persona"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["estado_actual_legajo_persona"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "estado_actual_legajo_personaadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "estado_actual_legajo_personadelete.php";
		$this->MultiUpdateUrl = "estado_actual_legajo_personaupdate.php";

		// Table object (personas)
		if (!isset($GLOBALS['personas'])) $GLOBALS['personas'] = new cpersonas();

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'estado_actual_legajo_persona', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (usuarios)
		if (!isset($UserTable)) {
			$UserTable = new cusuarios();
			$UserTableConn = Conn($UserTable->DBID);
		}

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption festado_actual_legajo_personalistsrch";

		// List actions
		$this->ListActions = new cListActions();
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
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Create form object
		$objForm = new cFormObj();

		// Get export parameters
		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Setup export options
		$this->SetupExportOptions();
		$this->Matricula->SetVisibility();
		$this->Certificado_Pase->SetVisibility();
		$this->Tiene_DNI->SetVisibility();
		$this->Certificado_Medico->SetVisibility();
		$this->Posee_Autorizacion->SetVisibility();
		$this->Cooperadora->SetVisibility();
		$this->Fecha_Actualizacion->SetVisibility();
		$this->Fecha_Actualizacion->Visible = !$this->IsAddOrEdit();
		$this->Usuario->SetVisibility();
		$this->Usuario->Visible = !$this->IsAddOrEdit();

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

		// Set up master detail parameters
		$this->SetUpMasterParms();

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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
		global $EW_EXPORT, $estado_actual_legajo_persona;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($estado_actual_legajo_persona);
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
			header("Location: " . $url);
		}
		exit();
	}

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 25;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Check QueryString parameters
			if (@$_GET["a"] <> "") {
				$this->CurrentAction = $_GET["a"];

				// Clear inline mode
				if ($this->CurrentAction == "cancel")
					$this->ClearInlineMode();

				// Switch to grid edit mode
				if ($this->CurrentAction == "gridedit")
					$this->GridEditMode();

				// Switch to inline edit mode
				if ($this->CurrentAction == "edit")
					$this->InlineEditMode();

				// Switch to inline add mode
				if ($this->CurrentAction == "add" || $this->CurrentAction == "copy")
					$this->InlineAddMode();

				// Switch to grid add mode
				if ($this->CurrentAction == "gridadd")
					$this->GridAddMode();
			} else {
				if (@$_POST["a_list"] <> "") {
					$this->CurrentAction = $_POST["a_list"]; // Get action

					// Grid Update
					if (($this->CurrentAction == "gridupdate" || $this->CurrentAction == "gridoverwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridedit") {
						if ($this->ValidateGridForm()) {
							$bGridUpdate = $this->GridUpdate();
						} else {
							$bGridUpdate = FALSE;
							$this->setFailureMessage($gsFormError);
						}
						if (!$bGridUpdate) {
							$this->EventCancelled = TRUE;
							$this->CurrentAction = "gridedit"; // Stay in Grid Edit mode
						}
					}

					// Inline Update
					if (($this->CurrentAction == "update" || $this->CurrentAction == "overwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "edit")
						$this->InlineUpdate();

					// Insert Inline
					if ($this->CurrentAction == "insert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "add")
						$this->InlineInsert();

					// Grid Insert
					if ($this->CurrentAction == "gridinsert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridadd") {
						if ($this->ValidateGridForm()) {
							$bGridInsert = $this->GridInsert();
						} else {
							$bGridInsert = FALSE;
							$this->setFailureMessage($gsFormError);
						}
						if (!$bGridInsert) {
							$this->EventCancelled = TRUE;
							$this->CurrentAction = "gridadd"; // Stay in Grid Add mode
						}
					}
				}
			}

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Show grid delete link for grid add / grid edit
			if ($this->AllowAddDeleteRow) {
				if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
					$item = $this->ListOptions->GetItem("griddelete");
					if ($item) $item->Visible = TRUE;
				}
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values

			// Process filter list
			$this->ProcessFilterList();
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 25; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records

		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "personas") {
			global $personas;
			$rsmaster = $personas->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("personaslist.php"); // Return to master page
			} else {
				$personas->LoadListRowValues($rsmaster);
				$personas->RowType = EW_ROWTYPE_MASTER; // Master row
				$personas->RenderListRow();
				$rsmaster->Close();
			}
		}

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Export data only
		if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	//  Exit inline mode
	function ClearInlineMode() {
		$this->setKey("Dni", ""); // Clear inline edit key
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}

	// Switch to Grid Add mode
	function GridAddMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridadd"; // Enabled grid add
	}

	// Switch to Grid Edit mode
	function GridEditMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridedit"; // Enable grid edit
	}

	// Switch to Inline Edit mode
	function InlineEditMode() {
		global $Security, $Language;
		if (!$Security->CanEdit())
			$this->Page_Terminate("login.php"); // Go to login page
		$bInlineEdit = TRUE;
		if (@$_GET["Dni"] <> "") {
			$this->Dni->setQueryStringValue($_GET["Dni"]);
		} else {
			$bInlineEdit = FALSE;
		}
		if ($bInlineEdit) {
			if ($this->LoadRow()) {
				$this->setKey("Dni", $this->Dni->CurrentValue); // Set up inline edit key
				$_SESSION[EW_SESSION_INLINE_MODE] = "edit"; // Enable inline edit
			}
		}
	}

	// Perform update to Inline Edit record
	function InlineUpdate() {
		global $Language, $objForm, $gsFormError;
		$objForm->Index = 1; 
		$this->LoadFormValues(); // Get form values

		// Validate form
		$bInlineUpdate = TRUE;
		if (!$this->ValidateForm()) {	
			$bInlineUpdate = FALSE; // Form error, reset action
			$this->setFailureMessage($gsFormError);
		} else {
			$bInlineUpdate = FALSE;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			if ($this->SetupKeyValues($rowkey)) { // Set up key values
				if ($this->CheckInlineEditKey()) { // Check key
					$this->SendEmail = TRUE; // Send email on update success
					$bInlineUpdate = $this->EditRow(); // Update record
				} else {
					$bInlineUpdate = FALSE;
				}
			}
		}
		if ($bInlineUpdate) { // Update success
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
			$this->EventCancelled = TRUE; // Cancel event
			$this->CurrentAction = "edit"; // Stay in edit mode
		}
	}

	// Check Inline Edit key
	function CheckInlineEditKey() {

		//CheckInlineEditKey = True
		if (strval($this->getKey("Dni")) <> strval($this->Dni->CurrentValue))
			return FALSE;
		return TRUE;
	}

	// Switch to Inline Add mode
	function InlineAddMode() {
		global $Security, $Language;
		if (!$Security->CanAdd())
			$this->Page_Terminate("login.php"); // Return to login page
		$this->CurrentAction = "add";
		$_SESSION[EW_SESSION_INLINE_MODE] = "add"; // Enable inline add
	}

	// Perform update to Inline Add/Copy record
	function InlineInsert() {
		global $Language, $objForm, $gsFormError;
		$this->LoadOldRecord(); // Load old recordset
		$objForm->Index = 0;
		$this->LoadFormValues(); // Get form values

		// Validate form
		if (!$this->ValidateForm()) {
			$this->setFailureMessage($gsFormError); // Set validation error message
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
			return;
		}
		$this->SendEmail = TRUE; // Send email on add success
		if ($this->AddRow($this->OldRecordset)) { // Add record
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up add success message
			$this->ClearInlineMode(); // Clear inline add mode
		} else { // Add failed
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
		}
	}

	// Perform update to grid
	function GridUpdate() {
		global $Language, $objForm, $gsFormError;
		$bGridUpdate = TRUE;

		// Get old recordset
		$this->CurrentFilter = $this->BuildKeyFilter();
		if ($this->CurrentFilter == "")
			$this->CurrentFilter = "0=1";
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			$rsold = $rs->GetRows();
			$rs->Close();
		}

		// Call Grid Updating event
		if (!$this->Grid_Updating($rsold)) {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("GridEditCancelled")); // Set grid edit cancelled message
			return FALSE;
		}

		// Begin transaction
		$conn->BeginTrans();
		if ($this->AuditTrailOnEdit) $this->WriteAuditTrailDummy($Language->Phrase("BatchUpdateBegin")); // Batch update begin
		$sKey = "";

		// Update row index and get row key
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Update all rows based on key
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
			$objForm->Index = $rowindex;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			$rowaction = strval($objForm->GetValue($this->FormActionName));

			// Load all values and keys
			if ($rowaction <> "insertdelete") { // Skip insert then deleted rows
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "" || $rowaction == "edit" || $rowaction == "delete") {
					$bGridUpdate = $this->SetupKeyValues($rowkey); // Set up key values
				} else {
					$bGridUpdate = TRUE;
				}

				// Skip empty row
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// No action required
				// Validate form and insert/update/delete record

				} elseif ($bGridUpdate) {
					if ($rowaction == "delete") {
						$this->CurrentFilter = $this->KeyFilter();
						$bGridUpdate = $this->DeleteRows(); // Delete this row
					} else if (!$this->ValidateForm()) {
						$bGridUpdate = FALSE; // Form error, reset action
						$this->setFailureMessage($gsFormError);
					} else {
						if ($rowaction == "insert") {
							$bGridUpdate = $this->AddRow(); // Insert this row
						} else {
							if ($rowkey <> "") {
								$this->SendEmail = FALSE; // Do not send email on update success
								$bGridUpdate = $this->EditRow(); // Update this row
							}
						} // End update
					}
				}
				if ($bGridUpdate) {
					if ($sKey <> "") $sKey .= ", ";
					$sKey .= $rowkey;
				} else {
					break;
				}
			}
		}
		if ($bGridUpdate) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Updated event
			$this->Grid_Updated($rsold, $rsnew);
			if ($this->AuditTrailOnEdit) $this->WriteAuditTrailDummy($Language->Phrase("BatchUpdateSuccess")); // Batch update success
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up update success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->AuditTrailOnEdit) $this->WriteAuditTrailDummy($Language->Phrase("BatchUpdateRollback")); // Batch update rollback
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
		}
		return $bGridUpdate;
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->Dni->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->Dni->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Perform Grid Add
	function GridInsert() {
		global $Language, $objForm, $gsFormError;
		$rowindex = 1;
		$bGridInsert = FALSE;
		$conn = &$this->Connection();

		// Call Grid Inserting event
		if (!$this->Grid_Inserting()) {
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("GridAddCancelled")); // Set grid add cancelled message
			}
			return FALSE;
		}

		// Begin transaction
		$conn->BeginTrans();

		// Init key filter
		$sWrkFilter = "";
		$addcnt = 0;
		if ($this->AuditTrailOnAdd) $this->WriteAuditTrailDummy($Language->Phrase("BatchInsertBegin")); // Batch insert begin
		$sKey = "";

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Insert all rows
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "" && $rowaction <> "insert")
				continue; // Skip
			$this->LoadFormValues(); // Get form values
			if (!$this->EmptyRow()) {
				$addcnt++;
				$this->SendEmail = FALSE; // Do not send email on insert success

				// Validate form
				if (!$this->ValidateForm()) {
					$bGridInsert = FALSE; // Form error, reset action
					$this->setFailureMessage($gsFormError);
				} else {
					$bGridInsert = $this->AddRow($this->OldRecordset); // Insert this row
				}
				if ($bGridInsert) {
					if ($sKey <> "") $sKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
					$sKey .= $this->Dni->CurrentValue;

					// Add filter for this record
					$sFilter = $this->KeyFilter();
					if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
					$sWrkFilter .= $sFilter;
				} else {
					break;
				}
			}
		}
		if ($addcnt == 0) { // No record inserted
			$this->setFailureMessage($Language->Phrase("NoAddRecord"));
			$bGridInsert = FALSE;
		}
		if ($bGridInsert) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			$this->CurrentFilter = $sWrkFilter;
			$sSql = $this->SQL();
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Inserted event
			$this->Grid_Inserted($rsnew);
			if ($this->AuditTrailOnAdd) $this->WriteAuditTrailDummy($Language->Phrase("BatchInsertSuccess")); // Batch insert success
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("InsertSuccess")); // Set up insert success message
			$this->ClearInlineMode(); // Clear grid add mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->AuditTrailOnAdd) $this->WriteAuditTrailDummy($Language->Phrase("BatchInsertRollback")); // Batch insert rollback
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("InsertFailed")); // Set insert failed message
			}
		}
		return $bGridInsert;
	}

	// Check if empty row
	function EmptyRow() {
		global $objForm;
		if ($objForm->HasValue("x_Matricula") && $objForm->HasValue("o_Matricula") && $this->Matricula->CurrentValue <> $this->Matricula->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_Certificado_Pase") && $objForm->HasValue("o_Certificado_Pase") && $this->Certificado_Pase->CurrentValue <> $this->Certificado_Pase->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_Tiene_DNI") && $objForm->HasValue("o_Tiene_DNI") && $this->Tiene_DNI->CurrentValue <> $this->Tiene_DNI->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_Certificado_Medico") && $objForm->HasValue("o_Certificado_Medico") && $this->Certificado_Medico->CurrentValue <> $this->Certificado_Medico->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_Posee_Autorizacion") && $objForm->HasValue("o_Posee_Autorizacion") && $this->Posee_Autorizacion->CurrentValue <> $this->Posee_Autorizacion->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_Cooperadora") && $objForm->HasValue("o_Cooperadora") && $this->Cooperadora->CurrentValue <> $this->Cooperadora->OldValue)
			return FALSE;
		return TRUE;
	}

	// Validate grid form
	function ValidateGridForm() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Validate all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else if (!$this->ValidateForm()) {
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	// Get all form values of the grid
	function GetGridFormValues() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;
		$rows = array();

		// Loop through all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else {
					$rows[] = $this->GetFieldValues("FormValue"); // Return row as array
				}
			}
		}
		return $rows; // Return as array of array
	}

	// Restore form values for current row
	function RestoreCurrentRowFormValues($idx) {
		global $objForm;

		// Get row based on current index
		$objForm->Index = $idx;
		$this->LoadFormValues(); // Load form values
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "festado_actual_legajo_personalistsrch");
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->Dni->AdvancedSearch->ToJSON(), ","); // Field Dni
		$sFilterList = ew_Concat($sFilterList, $this->Matricula->AdvancedSearch->ToJSON(), ","); // Field Matricula
		$sFilterList = ew_Concat($sFilterList, $this->Certificado_Pase->AdvancedSearch->ToJSON(), ","); // Field Certificado_Pase
		$sFilterList = ew_Concat($sFilterList, $this->Tiene_DNI->AdvancedSearch->ToJSON(), ","); // Field Tiene_DNI
		$sFilterList = ew_Concat($sFilterList, $this->Certificado_Medico->AdvancedSearch->ToJSON(), ","); // Field Certificado_Medico
		$sFilterList = ew_Concat($sFilterList, $this->Posee_Autorizacion->AdvancedSearch->ToJSON(), ","); // Field Posee_Autorizacion
		$sFilterList = ew_Concat($sFilterList, $this->Cooperadora->AdvancedSearch->ToJSON(), ","); // Field Cooperadora
		$sFilterList = ew_Concat($sFilterList, $this->Archivos_Varios->AdvancedSearch->ToJSON(), ","); // Field Archivos Varios
		$sFilterList = ew_Concat($sFilterList, $this->Fecha_Actualizacion->AdvancedSearch->ToJSON(), ","); // Field Fecha_Actualizacion
		$sFilterList = ew_Concat($sFilterList, $this->Usuario->AdvancedSearch->ToJSON(), ","); // Field Usuario
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}
		$sFilterList = preg_replace('/,$/', "", $sFilterList);

		// Return filter list in json
		if ($sFilterList <> "")
			$sFilterList = "\"data\":{" . $sFilterList . "}";
		if ($sSavedFilterList <> "") {
			if ($sFilterList <> "")
				$sFilterList .= ",";
			$sFilterList .= "\"filters\":" . $sSavedFilterList;
		}
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Process filter list
	function ProcessFilterList() {
		global $UserProfile;
		if (@$_POST["cmd"] == "savefilters") {
			$filters = ew_StripSlashes(@$_POST["filters"]);
			$UserProfile->SetSearchFilters(CurrentUserName(), "festado_actual_legajo_personalistsrch", $filters);
		} elseif (@$_POST["cmd"] == "resetfilter") {
			$this->RestoreFilterList();
		}
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(ew_StripSlashes(@$_POST["filter"]), TRUE);
		$this->Command = "search";

		// Field Dni
		$this->Dni->AdvancedSearch->SearchValue = @$filter["x_Dni"];
		$this->Dni->AdvancedSearch->SearchOperator = @$filter["z_Dni"];
		$this->Dni->AdvancedSearch->SearchCondition = @$filter["v_Dni"];
		$this->Dni->AdvancedSearch->SearchValue2 = @$filter["y_Dni"];
		$this->Dni->AdvancedSearch->SearchOperator2 = @$filter["w_Dni"];
		$this->Dni->AdvancedSearch->Save();

		// Field Matricula
		$this->Matricula->AdvancedSearch->SearchValue = @$filter["x_Matricula"];
		$this->Matricula->AdvancedSearch->SearchOperator = @$filter["z_Matricula"];
		$this->Matricula->AdvancedSearch->SearchCondition = @$filter["v_Matricula"];
		$this->Matricula->AdvancedSearch->SearchValue2 = @$filter["y_Matricula"];
		$this->Matricula->AdvancedSearch->SearchOperator2 = @$filter["w_Matricula"];
		$this->Matricula->AdvancedSearch->Save();

		// Field Certificado_Pase
		$this->Certificado_Pase->AdvancedSearch->SearchValue = @$filter["x_Certificado_Pase"];
		$this->Certificado_Pase->AdvancedSearch->SearchOperator = @$filter["z_Certificado_Pase"];
		$this->Certificado_Pase->AdvancedSearch->SearchCondition = @$filter["v_Certificado_Pase"];
		$this->Certificado_Pase->AdvancedSearch->SearchValue2 = @$filter["y_Certificado_Pase"];
		$this->Certificado_Pase->AdvancedSearch->SearchOperator2 = @$filter["w_Certificado_Pase"];
		$this->Certificado_Pase->AdvancedSearch->Save();

		// Field Tiene_DNI
		$this->Tiene_DNI->AdvancedSearch->SearchValue = @$filter["x_Tiene_DNI"];
		$this->Tiene_DNI->AdvancedSearch->SearchOperator = @$filter["z_Tiene_DNI"];
		$this->Tiene_DNI->AdvancedSearch->SearchCondition = @$filter["v_Tiene_DNI"];
		$this->Tiene_DNI->AdvancedSearch->SearchValue2 = @$filter["y_Tiene_DNI"];
		$this->Tiene_DNI->AdvancedSearch->SearchOperator2 = @$filter["w_Tiene_DNI"];
		$this->Tiene_DNI->AdvancedSearch->Save();

		// Field Certificado_Medico
		$this->Certificado_Medico->AdvancedSearch->SearchValue = @$filter["x_Certificado_Medico"];
		$this->Certificado_Medico->AdvancedSearch->SearchOperator = @$filter["z_Certificado_Medico"];
		$this->Certificado_Medico->AdvancedSearch->SearchCondition = @$filter["v_Certificado_Medico"];
		$this->Certificado_Medico->AdvancedSearch->SearchValue2 = @$filter["y_Certificado_Medico"];
		$this->Certificado_Medico->AdvancedSearch->SearchOperator2 = @$filter["w_Certificado_Medico"];
		$this->Certificado_Medico->AdvancedSearch->Save();

		// Field Posee_Autorizacion
		$this->Posee_Autorizacion->AdvancedSearch->SearchValue = @$filter["x_Posee_Autorizacion"];
		$this->Posee_Autorizacion->AdvancedSearch->SearchOperator = @$filter["z_Posee_Autorizacion"];
		$this->Posee_Autorizacion->AdvancedSearch->SearchCondition = @$filter["v_Posee_Autorizacion"];
		$this->Posee_Autorizacion->AdvancedSearch->SearchValue2 = @$filter["y_Posee_Autorizacion"];
		$this->Posee_Autorizacion->AdvancedSearch->SearchOperator2 = @$filter["w_Posee_Autorizacion"];
		$this->Posee_Autorizacion->AdvancedSearch->Save();

		// Field Cooperadora
		$this->Cooperadora->AdvancedSearch->SearchValue = @$filter["x_Cooperadora"];
		$this->Cooperadora->AdvancedSearch->SearchOperator = @$filter["z_Cooperadora"];
		$this->Cooperadora->AdvancedSearch->SearchCondition = @$filter["v_Cooperadora"];
		$this->Cooperadora->AdvancedSearch->SearchValue2 = @$filter["y_Cooperadora"];
		$this->Cooperadora->AdvancedSearch->SearchOperator2 = @$filter["w_Cooperadora"];
		$this->Cooperadora->AdvancedSearch->Save();

		// Field Archivos Varios
		$this->Archivos_Varios->AdvancedSearch->SearchValue = @$filter["x_Archivos_Varios"];
		$this->Archivos_Varios->AdvancedSearch->SearchOperator = @$filter["z_Archivos_Varios"];
		$this->Archivos_Varios->AdvancedSearch->SearchCondition = @$filter["v_Archivos_Varios"];
		$this->Archivos_Varios->AdvancedSearch->SearchValue2 = @$filter["y_Archivos_Varios"];
		$this->Archivos_Varios->AdvancedSearch->SearchOperator2 = @$filter["w_Archivos_Varios"];
		$this->Archivos_Varios->AdvancedSearch->Save();

		// Field Fecha_Actualizacion
		$this->Fecha_Actualizacion->AdvancedSearch->SearchValue = @$filter["x_Fecha_Actualizacion"];
		$this->Fecha_Actualizacion->AdvancedSearch->SearchOperator = @$filter["z_Fecha_Actualizacion"];
		$this->Fecha_Actualizacion->AdvancedSearch->SearchCondition = @$filter["v_Fecha_Actualizacion"];
		$this->Fecha_Actualizacion->AdvancedSearch->SearchValue2 = @$filter["y_Fecha_Actualizacion"];
		$this->Fecha_Actualizacion->AdvancedSearch->SearchOperator2 = @$filter["w_Fecha_Actualizacion"];
		$this->Fecha_Actualizacion->AdvancedSearch->Save();

		// Field Usuario
		$this->Usuario->AdvancedSearch->SearchValue = @$filter["x_Usuario"];
		$this->Usuario->AdvancedSearch->SearchOperator = @$filter["z_Usuario"];
		$this->Usuario->AdvancedSearch->SearchCondition = @$filter["v_Usuario"];
		$this->Usuario->AdvancedSearch->SearchValue2 = @$filter["y_Usuario"];
		$this->Usuario->AdvancedSearch->SearchOperator2 = @$filter["w_Usuario"];
		$this->Usuario->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->Dni, $Default, FALSE); // Dni
		$this->BuildSearchSql($sWhere, $this->Matricula, $Default, FALSE); // Matricula
		$this->BuildSearchSql($sWhere, $this->Certificado_Pase, $Default, FALSE); // Certificado_Pase
		$this->BuildSearchSql($sWhere, $this->Tiene_DNI, $Default, FALSE); // Tiene_DNI
		$this->BuildSearchSql($sWhere, $this->Certificado_Medico, $Default, FALSE); // Certificado_Medico
		$this->BuildSearchSql($sWhere, $this->Posee_Autorizacion, $Default, FALSE); // Posee_Autorizacion
		$this->BuildSearchSql($sWhere, $this->Cooperadora, $Default, FALSE); // Cooperadora
		$this->BuildSearchSql($sWhere, $this->Archivos_Varios, $Default, FALSE); // Archivos Varios
		$this->BuildSearchSql($sWhere, $this->Fecha_Actualizacion, $Default, FALSE); // Fecha_Actualizacion
		$this->BuildSearchSql($sWhere, $this->Usuario, $Default, FALSE); // Usuario

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->Dni->AdvancedSearch->Save(); // Dni
			$this->Matricula->AdvancedSearch->Save(); // Matricula
			$this->Certificado_Pase->AdvancedSearch->Save(); // Certificado_Pase
			$this->Tiene_DNI->AdvancedSearch->Save(); // Tiene_DNI
			$this->Certificado_Medico->AdvancedSearch->Save(); // Certificado_Medico
			$this->Posee_Autorizacion->AdvancedSearch->Save(); // Posee_Autorizacion
			$this->Cooperadora->AdvancedSearch->Save(); // Cooperadora
			$this->Archivos_Varios->AdvancedSearch->Save(); // Archivos Varios
			$this->Fecha_Actualizacion->AdvancedSearch->Save(); // Fecha_Actualizacion
			$this->Usuario->AdvancedSearch->Save(); // Usuario
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $Default, $MultiValue) {
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = ($Default) ? $Fld->AdvancedSearch->SearchValueDefault : $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = ($Default) ? $Fld->AdvancedSearch->SearchOperatorDefault : $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = ($Default) ? $Fld->AdvancedSearch->SearchConditionDefault : $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = ($Default) ? $Fld->AdvancedSearch->SearchValue2Default : $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = ($Default) ? $Fld->AdvancedSearch->SearchOperator2Default : $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";

		//$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);

		//$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1 || $FldOpr <> "LIKE" ||
			($FldOpr2 <> "LIKE" && $FldVal2 <> ""))
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal, $this->DBID) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2, $this->DBID) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2, $this->DBID);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE || $Fld->FldDataType == EW_DATATYPE_TIME) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->Dni, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Matricula, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Certificado_Pase, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Tiene_DNI, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Certificado_Medico, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Posee_Autorizacion, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Cooperadora, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Archivos_Varios, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Usuario, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $arKeywords, $type) {
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if (EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace(EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual && $Fld->FldVirtualSearch) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .=  "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				$ar = array();

				// Match quoted keywords (i.e.: "...")
				if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$p = strpos($sSearch, $match[0]);
						$str = substr($sSearch, 0, $p);
						$sSearch = substr($sSearch, $p + strlen($match[0]));
						if (strlen(trim($str)) > 0)
							$ar = array_merge($ar, explode(" ", trim($str)));
						$ar[] = $match[1]; // Save quoted keyword
					}
				}

				// Match individual keywords
				if (strlen(trim($sSearch)) > 0)
					$ar = array_merge($ar, explode(" ", trim($sSearch)));

				// Search keyword in any fields
				if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
					foreach ($ar as $sKeyword) {
						if ($sKeyword <> "") {
							if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
							$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
						}
					}
				} else {
					$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL(array($sSearch), $sSearchType);
			}
			if (!$Default) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		if ($this->Dni->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Matricula->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Certificado_Pase->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Tiene_DNI->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Certificado_Medico->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Posee_Autorizacion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Cooperadora->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Archivos_Varios->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Fecha_Actualizacion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Usuario->AdvancedSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->Dni->AdvancedSearch->UnsetSession();
		$this->Matricula->AdvancedSearch->UnsetSession();
		$this->Certificado_Pase->AdvancedSearch->UnsetSession();
		$this->Tiene_DNI->AdvancedSearch->UnsetSession();
		$this->Certificado_Medico->AdvancedSearch->UnsetSession();
		$this->Posee_Autorizacion->AdvancedSearch->UnsetSession();
		$this->Cooperadora->AdvancedSearch->UnsetSession();
		$this->Archivos_Varios->AdvancedSearch->UnsetSession();
		$this->Fecha_Actualizacion->AdvancedSearch->UnsetSession();
		$this->Usuario->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->Dni->AdvancedSearch->Load();
		$this->Matricula->AdvancedSearch->Load();
		$this->Certificado_Pase->AdvancedSearch->Load();
		$this->Tiene_DNI->AdvancedSearch->Load();
		$this->Certificado_Medico->AdvancedSearch->Load();
		$this->Posee_Autorizacion->AdvancedSearch->Load();
		$this->Cooperadora->AdvancedSearch->Load();
		$this->Archivos_Varios->AdvancedSearch->Load();
		$this->Fecha_Actualizacion->AdvancedSearch->Load();
		$this->Usuario->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->Matricula); // Matricula
			$this->UpdateSort($this->Certificado_Pase); // Certificado_Pase
			$this->UpdateSort($this->Tiene_DNI); // Tiene_DNI
			$this->UpdateSort($this->Certificado_Medico); // Certificado_Medico
			$this->UpdateSort($this->Posee_Autorizacion); // Posee_Autorizacion
			$this->UpdateSort($this->Cooperadora); // Cooperadora
			$this->UpdateSort($this->Fecha_Actualizacion); // Fecha_Actualizacion
			$this->UpdateSort($this->Usuario); // Usuario
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->Dni->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->Matricula->setSort("");
				$this->Certificado_Pase->setSort("");
				$this->Tiene_DNI->setSort("");
				$this->Certificado_Medico->setSort("");
				$this->Posee_Autorizacion->setSort("");
				$this->Cooperadora->setSort("");
				$this->Fecha_Actualizacion->setSort("");
				$this->Usuario->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// "griddelete"
		if ($this->AllowAddDeleteRow) {
			$item = &$this->ListOptions->Add("griddelete");
			$item->CssStyle = "white-space: nowrap;";
			$item->OnLeft = TRUE;
			$item->Visible = FALSE; // Default hidden
		}

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = TRUE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = TRUE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanAdd();
		$item->OnLeft = TRUE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = ($Security->CanDelete() || $Security->CanEdit());
		$item->OnLeft = TRUE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->MoveTo(0);
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// Set up row action and key
		if (is_numeric($this->RowIndex) && $this->CurrentMode <> "view") {
			$objForm->Index = $this->RowIndex;
			$ActionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
			$OldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormOldKeyName);
			$KeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormKeyName);
			$BlankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
			if ($this->RowAction <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $ActionName . "\" id=\"" . $ActionName . "\" value=\"" . $this->RowAction . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue($this->FormKeyName);
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $BlankRowName . "\" id=\"" . $BlankRowName . "\" value=\"1\">";
		}

		// "delete"
		if ($this->AllowAddDeleteRow) {
			if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$option = &$this->ListOptions;
				$option->UseButtonGroup = TRUE; // Use button group for grid delete button
				$option->UseImageAndText = TRUE; // Use image and text for grid delete button
				$oListOpt = &$option->Items["griddelete"];
				if (!$Security->CanDelete() && is_numeric($this->RowIndex) && ($this->RowAction == "" || $this->RowAction == "edit")) { // Do not allow delete existing record
					$oListOpt->Body = "&nbsp;";
				} else {
					$oListOpt->Body = "<a class=\"ewGridLink ewGridDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" onclick=\"return ew_DeleteGridRow(this, " . $this->RowIndex . ");\">" . $Language->Phrase("DeleteLink") . "</a>";
				}
			}
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if (($this->CurrentAction == "add" || $this->CurrentAction == "copy") && $this->RowType == EW_ROWTYPE_ADD) { // Inline Add/Copy
			$this->ListOptions->CustomItem = "copy"; // Show copy column only
			$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
			$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
				"<a class=\"ewGridLink ewInlineInsert\" title=\"" . ew_HtmlTitle($Language->Phrase("InsertLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InsertLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . $this->PageName() . "');\">" . $Language->Phrase("InsertLink") . "</a>&nbsp;" .
				"<a class=\"ewGridLink ewInlineCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("CancelLink") . "</a>" .
				"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"insert\"></div>";
			return;
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($this->CurrentAction == "edit" && $this->RowType == EW_ROWTYPE_EDIT) { // Inline-Edit
			$this->ListOptions->CustomItem = "edit"; // Show edit column only
			$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
				$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
					"<a class=\"ewGridLink ewInlineUpdate\" title=\"" . ew_HtmlTitle($Language->Phrase("UpdateLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("UpdateLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . ew_GetHashUrl($this->PageName(), $this->PageObjName . "_row_" . $this->RowCnt) . "');\">" . $Language->Phrase("UpdateLink") . "</a>&nbsp;" .
					"<a class=\"ewGridLink ewInlineCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("CancelLink") . "</a>" .
					"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"update\"></div>";
			$oListOpt->Body .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_key\" id=\"k" . $this->RowIndex . "_key\" value=\"" . ew_HtmlEncode($this->Dni->CurrentValue) . "\">";
			return;
		}

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		$viewcaption = ew_HtmlTitle($Language->Phrase("ViewLink"));
		if ($Security->CanView()) {
			if (ew_IsMobile())
				$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
			else
				$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-table=\"estado_actual_legajo_persona\" data-caption=\"" . $viewcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->ViewUrl) . "'});\">" . $Language->Phrase("ViewLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if ($Security->CanEdit()) {
			if (ew_IsMobile())
				$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
			else
				$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . $editcaption . "\" data-table=\"estado_actual_legajo_persona\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->EditUrl) . "',caption:'" . $editcaption . "'});\">" . $Language->Phrase("EditLink") . "</a>";
			$oListOpt->Body .= "<a class=\"ewRowLink ewInlineEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" href=\"" . ew_HtmlEncode(ew_GetHashUrl($this->InlineEditUrl, $this->PageObjName . "_row_" . $this->RowCnt)) . "\">" . $Language->Phrase("InlineEditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		$copycaption = ew_HtmlTitle($Language->Phrase("CopyLink"));
		if ($Security->CanAdd()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt && $this->Export == "" && $this->CurrentAction == "") {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->Dni->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
		if ($this->CurrentAction == "gridedit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->Dni->CurrentValue . "\">";
		}
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("AddLink"));
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Inline Add
		$item = &$option->Add("inlineadd");
		$item->Body = "<a class=\"ewAddEdit ewInlineAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineAddLink")) . "\" href=\"" . ew_HtmlEncode($this->InlineAddUrl) . "\">" .$Language->Phrase("InlineAddLink") . "</a>";
		$item->Visible = ($this->InlineAddUrl <> "" && $Security->CanAdd());
		$item = &$option->Add("gridadd");
		$item->Body = "<a class=\"ewAddEdit ewGridAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("GridAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridAddLink")) . "\" href=\"" . ew_HtmlEncode($this->GridAddUrl) . "\">" . $Language->Phrase("GridAddLink") . "</a>";
		$item->Visible = ($this->GridAddUrl <> "" && $Security->CanAdd());

		// Add grid edit
		$option = $options["addedit"];
		$item = &$option->Add("gridedit");
		$item->Body = "<a class=\"ewAddEdit ewGridEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GridEditUrl) . "\">" . $Language->Phrase("GridEditLink") . "</a>";
		$item->Visible = ($this->GridEditUrl <> "" && $Security->CanEdit());
		$option = $options["action"];

		// Add multi delete
		$item = &$option->Add("multidelete");
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitAction(event,{f:document.festado_actual_legajo_personalist,url:'" . $this->MultiDeleteUrl . "',msg:ewLanguage.Phrase('DeleteConfirmMsg')});return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
		$item->Visible = ($Security->CanDelete());

		// Add multi update
		$item = &$option->Add("multiupdate");
		$item->Body = "<a class=\"ewAction ewMultiUpdate\" title=\"" . ew_HtmlTitle($Language->Phrase("UpdateSelectedLink")) . "\" data-table=\"estado_actual_legajo_persona\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("UpdateSelectedLink")) . "\" href=\"\" onclick=\"ew_ModalDialogShow({lnk:this,f:document.festado_actual_legajo_personalist,url:'" . $this->MultiUpdateUrl . "',caption:'" . $Language->Phrase("UpdateBtn") . "'});return false;\">" . $Language->Phrase("UpdateSelectedLink") . "</a>";
		$item->Visible = ($Security->CanEdit());

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"festado_actual_legajo_personalistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"festado_actual_legajo_personalistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "gridedit") { // Not grid add/edit mode
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.festado_actual_legajo_personalist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
		} else { // Grid add/edit mode

			// Hide all options first
			foreach ($options as &$option)
				$option->HideAllOptions();
			if ($this->CurrentAction == "gridadd") {
				if ($this->AllowAddDeleteRow) {

					// Add add blank row
					$option = &$options["addedit"];
					$option->UseDropDownButton = FALSE;
					$option->UseImageAndText = TRUE;
					$item = &$option->Add("addblankrow");
					$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" title=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
					$item->Visible = $Security->CanAdd();
				}
				$option = &$options["action"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;

				// Add grid insert
				$item = &$option->Add("gridinsert");
				$item->Body = "<a class=\"ewAction ewGridInsert\" title=\"" . ew_HtmlTitle($Language->Phrase("GridInsertLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridInsertLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . $this->PageName() . "');\">" . $Language->Phrase("GridInsertLink") . "</a>";

				// Add grid cancel
				$item = &$option->Add("gridcancel");
				$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
				$item->Body = "<a class=\"ewAction ewGridCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("GridCancelLink") . "</a>";
			}
			if ($this->CurrentAction == "gridedit") {
				if ($this->AllowAddDeleteRow) {

					// Add add blank row
					$option = &$options["addedit"];
					$option->UseDropDownButton = FALSE;
					$option->UseImageAndText = TRUE;
					$item = &$option->Add("addblankrow");
					$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" title=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
					$item->Visible = $Security->CanAdd();
				}
				$option = &$options["action"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;
					$item = &$option->Add("gridsave");
					$item->Body = "<a class=\"ewAction ewGridSave\" title=\"" . ew_HtmlTitle($Language->Phrase("GridSaveLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridSaveLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . $this->PageName() . "');\">" . $Language->Phrase("GridSaveLink") . "</a>";
					$item = &$option->Add("gridcancel");
					$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
					$item->Body = "<a class=\"ewAction ewGridCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("GridCancelLink") . "</a>";
			}
		}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"festado_actual_legajo_personalistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Advanced search button
		$item = &$this->SearchOptions->Add("advancedsearch");
		$item->Body = "<a class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" href=\"estado_actual_legajo_personasrch.php\">" . $Language->Phrase("AdvancedSearchBtn") . "</a>";
		$item->Visible = TRUE;

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch()) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Load default values
	function LoadDefaultValues() {
		$this->Matricula->CurrentValue = 'SI';
		$this->Matricula->OldValue = $this->Matricula->CurrentValue;
		$this->Certificado_Pase->CurrentValue = 'SI';
		$this->Certificado_Pase->OldValue = $this->Certificado_Pase->CurrentValue;
		$this->Tiene_DNI->CurrentValue = 'SI';
		$this->Tiene_DNI->OldValue = $this->Tiene_DNI->CurrentValue;
		$this->Certificado_Medico->CurrentValue = 'SI';
		$this->Certificado_Medico->OldValue = $this->Certificado_Medico->CurrentValue;
		$this->Posee_Autorizacion->CurrentValue = 'SI';
		$this->Posee_Autorizacion->OldValue = $this->Posee_Autorizacion->CurrentValue;
		$this->Cooperadora->CurrentValue = 'SI';
		$this->Cooperadora->OldValue = $this->Cooperadora->CurrentValue;
		$this->Fecha_Actualizacion->CurrentValue = NULL;
		$this->Fecha_Actualizacion->OldValue = $this->Fecha_Actualizacion->CurrentValue;
		$this->Usuario->CurrentValue = NULL;
		$this->Usuario->OldValue = $this->Usuario->CurrentValue;
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// Dni

		$this->Dni->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Dni"]);
		if ($this->Dni->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Dni->AdvancedSearch->SearchOperator = @$_GET["z_Dni"];

		// Matricula
		$this->Matricula->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Matricula"]);
		if ($this->Matricula->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Matricula->AdvancedSearch->SearchOperator = @$_GET["z_Matricula"];

		// Certificado_Pase
		$this->Certificado_Pase->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Certificado_Pase"]);
		if ($this->Certificado_Pase->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Certificado_Pase->AdvancedSearch->SearchOperator = @$_GET["z_Certificado_Pase"];

		// Tiene_DNI
		$this->Tiene_DNI->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Tiene_DNI"]);
		if ($this->Tiene_DNI->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Tiene_DNI->AdvancedSearch->SearchOperator = @$_GET["z_Tiene_DNI"];

		// Certificado_Medico
		$this->Certificado_Medico->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Certificado_Medico"]);
		if ($this->Certificado_Medico->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Certificado_Medico->AdvancedSearch->SearchOperator = @$_GET["z_Certificado_Medico"];

		// Posee_Autorizacion
		$this->Posee_Autorizacion->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Posee_Autorizacion"]);
		if ($this->Posee_Autorizacion->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Posee_Autorizacion->AdvancedSearch->SearchOperator = @$_GET["z_Posee_Autorizacion"];

		// Cooperadora
		$this->Cooperadora->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Cooperadora"]);
		if ($this->Cooperadora->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Cooperadora->AdvancedSearch->SearchOperator = @$_GET["z_Cooperadora"];

		// Archivos Varios
		$this->Archivos_Varios->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Archivos_Varios"]);
		if ($this->Archivos_Varios->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Archivos_Varios->AdvancedSearch->SearchOperator = @$_GET["z_Archivos_Varios"];

		// Fecha_Actualizacion
		$this->Fecha_Actualizacion->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Fecha_Actualizacion"]);
		if ($this->Fecha_Actualizacion->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Fecha_Actualizacion->AdvancedSearch->SearchOperator = @$_GET["z_Fecha_Actualizacion"];

		// Usuario
		$this->Usuario->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Usuario"]);
		if ($this->Usuario->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Usuario->AdvancedSearch->SearchOperator = @$_GET["z_Usuario"];
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->Matricula->FldIsDetailKey) {
			$this->Matricula->setFormValue($objForm->GetValue("x_Matricula"));
		}
		$this->Matricula->setOldValue($objForm->GetValue("o_Matricula"));
		if (!$this->Certificado_Pase->FldIsDetailKey) {
			$this->Certificado_Pase->setFormValue($objForm->GetValue("x_Certificado_Pase"));
		}
		$this->Certificado_Pase->setOldValue($objForm->GetValue("o_Certificado_Pase"));
		if (!$this->Tiene_DNI->FldIsDetailKey) {
			$this->Tiene_DNI->setFormValue($objForm->GetValue("x_Tiene_DNI"));
		}
		$this->Tiene_DNI->setOldValue($objForm->GetValue("o_Tiene_DNI"));
		if (!$this->Certificado_Medico->FldIsDetailKey) {
			$this->Certificado_Medico->setFormValue($objForm->GetValue("x_Certificado_Medico"));
		}
		$this->Certificado_Medico->setOldValue($objForm->GetValue("o_Certificado_Medico"));
		if (!$this->Posee_Autorizacion->FldIsDetailKey) {
			$this->Posee_Autorizacion->setFormValue($objForm->GetValue("x_Posee_Autorizacion"));
		}
		$this->Posee_Autorizacion->setOldValue($objForm->GetValue("o_Posee_Autorizacion"));
		if (!$this->Cooperadora->FldIsDetailKey) {
			$this->Cooperadora->setFormValue($objForm->GetValue("x_Cooperadora"));
		}
		$this->Cooperadora->setOldValue($objForm->GetValue("o_Cooperadora"));
		if (!$this->Fecha_Actualizacion->FldIsDetailKey) {
			$this->Fecha_Actualizacion->setFormValue($objForm->GetValue("x_Fecha_Actualizacion"));
			$this->Fecha_Actualizacion->CurrentValue = ew_UnFormatDateTime($this->Fecha_Actualizacion->CurrentValue, 7);
		}
		$this->Fecha_Actualizacion->setOldValue($objForm->GetValue("o_Fecha_Actualizacion"));
		if (!$this->Usuario->FldIsDetailKey) {
			$this->Usuario->setFormValue($objForm->GetValue("x_Usuario"));
		}
		$this->Usuario->setOldValue($objForm->GetValue("o_Usuario"));
		if (!$this->Dni->FldIsDetailKey)
			$this->Dni->setFormValue($objForm->GetValue("x_Dni"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->Dni->CurrentValue = $this->Dni->FormValue;
		$this->Matricula->CurrentValue = $this->Matricula->FormValue;
		$this->Certificado_Pase->CurrentValue = $this->Certificado_Pase->FormValue;
		$this->Tiene_DNI->CurrentValue = $this->Tiene_DNI->FormValue;
		$this->Certificado_Medico->CurrentValue = $this->Certificado_Medico->FormValue;
		$this->Posee_Autorizacion->CurrentValue = $this->Posee_Autorizacion->FormValue;
		$this->Cooperadora->CurrentValue = $this->Cooperadora->FormValue;
		$this->Fecha_Actualizacion->CurrentValue = $this->Fecha_Actualizacion->FormValue;
		$this->Fecha_Actualizacion->CurrentValue = ew_UnFormatDateTime($this->Fecha_Actualizacion->CurrentValue, 7);
		$this->Usuario->CurrentValue = $this->Usuario->FormValue;
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
		$this->Dni->setDbValue($rs->fields('Dni'));
		$this->Matricula->setDbValue($rs->fields('Matricula'));
		$this->Certificado_Pase->setDbValue($rs->fields('Certificado_Pase'));
		$this->Tiene_DNI->setDbValue($rs->fields('Tiene_DNI'));
		$this->Certificado_Medico->setDbValue($rs->fields('Certificado_Medico'));
		$this->Posee_Autorizacion->setDbValue($rs->fields('Posee_Autorizacion'));
		$this->Cooperadora->setDbValue($rs->fields('Cooperadora'));
		$this->Archivos_Varios->Upload->DbValue = $rs->fields('Archivos Varios');
		$this->Archivos_Varios->CurrentValue = $this->Archivos_Varios->Upload->DbValue;
		$this->Fecha_Actualizacion->setDbValue($rs->fields('Fecha_Actualizacion'));
		$this->Usuario->setDbValue($rs->fields('Usuario'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Dni->DbValue = $row['Dni'];
		$this->Matricula->DbValue = $row['Matricula'];
		$this->Certificado_Pase->DbValue = $row['Certificado_Pase'];
		$this->Tiene_DNI->DbValue = $row['Tiene_DNI'];
		$this->Certificado_Medico->DbValue = $row['Certificado_Medico'];
		$this->Posee_Autorizacion->DbValue = $row['Posee_Autorizacion'];
		$this->Cooperadora->DbValue = $row['Cooperadora'];
		$this->Archivos_Varios->Upload->DbValue = $row['Archivos Varios'];
		$this->Fecha_Actualizacion->DbValue = $row['Fecha_Actualizacion'];
		$this->Usuario->DbValue = $row['Usuario'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("Dni")) <> "")
			$this->Dni->CurrentValue = $this->getKey("Dni"); // Dni
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// Dni
		// Matricula
		// Certificado_Pase
		// Tiene_DNI
		// Certificado_Medico
		// Posee_Autorizacion
		// Cooperadora
		// Archivos Varios
		// Fecha_Actualizacion
		// Usuario

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// Dni
		$this->Dni->ViewValue = $this->Dni->CurrentValue;
		$this->Dni->ViewCustomAttributes = "";

		// Matricula
		if (strval($this->Matricula->CurrentValue) <> "") {
			$this->Matricula->ViewValue = $this->Matricula->OptionCaption($this->Matricula->CurrentValue);
		} else {
			$this->Matricula->ViewValue = NULL;
		}
		$this->Matricula->ViewCustomAttributes = "";

		// Certificado_Pase
		if (strval($this->Certificado_Pase->CurrentValue) <> "") {
			$this->Certificado_Pase->ViewValue = $this->Certificado_Pase->OptionCaption($this->Certificado_Pase->CurrentValue);
		} else {
			$this->Certificado_Pase->ViewValue = NULL;
		}
		$this->Certificado_Pase->ViewCustomAttributes = "";

		// Tiene_DNI
		if (strval($this->Tiene_DNI->CurrentValue) <> "") {
			$this->Tiene_DNI->ViewValue = $this->Tiene_DNI->OptionCaption($this->Tiene_DNI->CurrentValue);
		} else {
			$this->Tiene_DNI->ViewValue = NULL;
		}
		$this->Tiene_DNI->ViewCustomAttributes = "";

		// Certificado_Medico
		if (strval($this->Certificado_Medico->CurrentValue) <> "") {
			$this->Certificado_Medico->ViewValue = $this->Certificado_Medico->OptionCaption($this->Certificado_Medico->CurrentValue);
		} else {
			$this->Certificado_Medico->ViewValue = NULL;
		}
		$this->Certificado_Medico->ViewCustomAttributes = "";

		// Posee_Autorizacion
		if (strval($this->Posee_Autorizacion->CurrentValue) <> "") {
			$this->Posee_Autorizacion->ViewValue = $this->Posee_Autorizacion->OptionCaption($this->Posee_Autorizacion->CurrentValue);
		} else {
			$this->Posee_Autorizacion->ViewValue = NULL;
		}
		$this->Posee_Autorizacion->ViewCustomAttributes = "";

		// Cooperadora
		if (strval($this->Cooperadora->CurrentValue) <> "") {
			$this->Cooperadora->ViewValue = $this->Cooperadora->OptionCaption($this->Cooperadora->CurrentValue);
		} else {
			$this->Cooperadora->ViewValue = NULL;
		}
		$this->Cooperadora->ViewCustomAttributes = "";

		// Fecha_Actualizacion
		$this->Fecha_Actualizacion->ViewValue = $this->Fecha_Actualizacion->CurrentValue;
		$this->Fecha_Actualizacion->ViewValue = ew_FormatDateTime($this->Fecha_Actualizacion->ViewValue, 7);
		$this->Fecha_Actualizacion->ViewCustomAttributes = "";

		// Usuario
		$this->Usuario->ViewValue = $this->Usuario->CurrentValue;
		$this->Usuario->ViewCustomAttributes = "";

			// Matricula
			$this->Matricula->LinkCustomAttributes = "";
			$this->Matricula->HrefValue = "";
			$this->Matricula->TooltipValue = "";

			// Certificado_Pase
			$this->Certificado_Pase->LinkCustomAttributes = "";
			$this->Certificado_Pase->HrefValue = "";
			$this->Certificado_Pase->TooltipValue = "";

			// Tiene_DNI
			$this->Tiene_DNI->LinkCustomAttributes = "";
			$this->Tiene_DNI->HrefValue = "";
			$this->Tiene_DNI->TooltipValue = "";

			// Certificado_Medico
			$this->Certificado_Medico->LinkCustomAttributes = "";
			$this->Certificado_Medico->HrefValue = "";
			$this->Certificado_Medico->TooltipValue = "";

			// Posee_Autorizacion
			$this->Posee_Autorizacion->LinkCustomAttributes = "";
			$this->Posee_Autorizacion->HrefValue = "";
			$this->Posee_Autorizacion->TooltipValue = "";

			// Cooperadora
			$this->Cooperadora->LinkCustomAttributes = "";
			$this->Cooperadora->HrefValue = "";
			$this->Cooperadora->TooltipValue = "";

			// Fecha_Actualizacion
			$this->Fecha_Actualizacion->LinkCustomAttributes = "";
			$this->Fecha_Actualizacion->HrefValue = "";
			$this->Fecha_Actualizacion->TooltipValue = "";

			// Usuario
			$this->Usuario->LinkCustomAttributes = "";
			$this->Usuario->HrefValue = "";
			$this->Usuario->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// Matricula
			$this->Matricula->EditCustomAttributes = "";
			$this->Matricula->EditValue = $this->Matricula->Options(FALSE);

			// Certificado_Pase
			$this->Certificado_Pase->EditCustomAttributes = "";
			$this->Certificado_Pase->EditValue = $this->Certificado_Pase->Options(FALSE);

			// Tiene_DNI
			$this->Tiene_DNI->EditCustomAttributes = "";
			$this->Tiene_DNI->EditValue = $this->Tiene_DNI->Options(FALSE);

			// Certificado_Medico
			$this->Certificado_Medico->EditCustomAttributes = "";
			$this->Certificado_Medico->EditValue = $this->Certificado_Medico->Options(FALSE);

			// Posee_Autorizacion
			$this->Posee_Autorizacion->EditCustomAttributes = "";
			$this->Posee_Autorizacion->EditValue = $this->Posee_Autorizacion->Options(FALSE);

			// Cooperadora
			$this->Cooperadora->EditCustomAttributes = "";
			$this->Cooperadora->EditValue = $this->Cooperadora->Options(FALSE);

			// Fecha_Actualizacion
			// Usuario
			// Add refer script
			// Matricula

			$this->Matricula->LinkCustomAttributes = "";
			$this->Matricula->HrefValue = "";

			// Certificado_Pase
			$this->Certificado_Pase->LinkCustomAttributes = "";
			$this->Certificado_Pase->HrefValue = "";

			// Tiene_DNI
			$this->Tiene_DNI->LinkCustomAttributes = "";
			$this->Tiene_DNI->HrefValue = "";

			// Certificado_Medico
			$this->Certificado_Medico->LinkCustomAttributes = "";
			$this->Certificado_Medico->HrefValue = "";

			// Posee_Autorizacion
			$this->Posee_Autorizacion->LinkCustomAttributes = "";
			$this->Posee_Autorizacion->HrefValue = "";

			// Cooperadora
			$this->Cooperadora->LinkCustomAttributes = "";
			$this->Cooperadora->HrefValue = "";

			// Fecha_Actualizacion
			$this->Fecha_Actualizacion->LinkCustomAttributes = "";
			$this->Fecha_Actualizacion->HrefValue = "";

			// Usuario
			$this->Usuario->LinkCustomAttributes = "";
			$this->Usuario->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// Matricula
			$this->Matricula->EditCustomAttributes = "";
			$this->Matricula->EditValue = $this->Matricula->Options(FALSE);

			// Certificado_Pase
			$this->Certificado_Pase->EditCustomAttributes = "";
			$this->Certificado_Pase->EditValue = $this->Certificado_Pase->Options(FALSE);

			// Tiene_DNI
			$this->Tiene_DNI->EditCustomAttributes = "";
			$this->Tiene_DNI->EditValue = $this->Tiene_DNI->Options(FALSE);

			// Certificado_Medico
			$this->Certificado_Medico->EditCustomAttributes = "";
			$this->Certificado_Medico->EditValue = $this->Certificado_Medico->Options(FALSE);

			// Posee_Autorizacion
			$this->Posee_Autorizacion->EditCustomAttributes = "";
			$this->Posee_Autorizacion->EditValue = $this->Posee_Autorizacion->Options(FALSE);

			// Cooperadora
			$this->Cooperadora->EditCustomAttributes = "";
			$this->Cooperadora->EditValue = $this->Cooperadora->Options(FALSE);

			// Fecha_Actualizacion
			// Usuario
			// Edit refer script
			// Matricula

			$this->Matricula->LinkCustomAttributes = "";
			$this->Matricula->HrefValue = "";

			// Certificado_Pase
			$this->Certificado_Pase->LinkCustomAttributes = "";
			$this->Certificado_Pase->HrefValue = "";

			// Tiene_DNI
			$this->Tiene_DNI->LinkCustomAttributes = "";
			$this->Tiene_DNI->HrefValue = "";

			// Certificado_Medico
			$this->Certificado_Medico->LinkCustomAttributes = "";
			$this->Certificado_Medico->HrefValue = "";

			// Posee_Autorizacion
			$this->Posee_Autorizacion->LinkCustomAttributes = "";
			$this->Posee_Autorizacion->HrefValue = "";

			// Cooperadora
			$this->Cooperadora->LinkCustomAttributes = "";
			$this->Cooperadora->HrefValue = "";

			// Fecha_Actualizacion
			$this->Fecha_Actualizacion->LinkCustomAttributes = "";
			$this->Fecha_Actualizacion->HrefValue = "";

			// Usuario
			$this->Usuario->LinkCustomAttributes = "";
			$this->Usuario->HrefValue = "";
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

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");

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

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteBegin")); // Batch delete begin

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['Dni'];
				$this->LoadDbValues($row);
				$this->Archivos_Varios->OldUploadPath = 'ArchivosLegajoPersonas';
				$OldFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $row['Archivos Varios']);
				$FileCount = count($OldFiles);
				for ($i = 0; $i < $FileCount; $i++) {
					@unlink(ew_UploadPathEx(TRUE, $this->Archivos_Varios->OldUploadPath) . $OldFiles[$i]);
				}
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			if ($DeleteRows) {
				foreach ($rsold as $row)
					$this->WriteAuditTrailOnDelete($row);
			}
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteSuccess")); // Batch delete success
		} else {
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
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
			$this->Archivos_Varios->OldUploadPath = 'ArchivosLegajoPersonas';
			$this->Archivos_Varios->UploadPath = $this->Archivos_Varios->OldUploadPath;
			$rsnew = array();

			// Matricula
			$this->Matricula->SetDbValueDef($rsnew, $this->Matricula->CurrentValue, NULL, $this->Matricula->ReadOnly);

			// Certificado_Pase
			$this->Certificado_Pase->SetDbValueDef($rsnew, $this->Certificado_Pase->CurrentValue, NULL, $this->Certificado_Pase->ReadOnly);

			// Tiene_DNI
			$this->Tiene_DNI->SetDbValueDef($rsnew, $this->Tiene_DNI->CurrentValue, NULL, $this->Tiene_DNI->ReadOnly);

			// Certificado_Medico
			$this->Certificado_Medico->SetDbValueDef($rsnew, $this->Certificado_Medico->CurrentValue, NULL, $this->Certificado_Medico->ReadOnly);

			// Posee_Autorizacion
			$this->Posee_Autorizacion->SetDbValueDef($rsnew, $this->Posee_Autorizacion->CurrentValue, NULL, $this->Posee_Autorizacion->ReadOnly);

			// Cooperadora
			$this->Cooperadora->SetDbValueDef($rsnew, $this->Cooperadora->CurrentValue, NULL, $this->Cooperadora->ReadOnly);

			// Fecha_Actualizacion
			$this->Fecha_Actualizacion->SetDbValueDef($rsnew, ew_CurrentDate(), NULL);
			$rsnew['Fecha_Actualizacion'] = &$this->Fecha_Actualizacion->DbValue;

			// Usuario
			$this->Usuario->SetDbValueDef($rsnew, CurrentUserName(), NULL);
			$rsnew['Usuario'] = &$this->Usuario->DbValue;

			// Check referential integrity for master table 'personas'
			$bValidMasterRecord = TRUE;
			$sMasterFilter = $this->SqlMasterFilter_personas();
			$KeyValue = isset($rsnew['Dni']) ? $rsnew['Dni'] : $rsold['Dni'];
			if (strval($KeyValue) <> "") {
				$sMasterFilter = str_replace("@Dni@", ew_AdjustSql($KeyValue), $sMasterFilter);
			} else {
				$bValidMasterRecord = FALSE;
			}
			if ($bValidMasterRecord) {
				if (!isset($GLOBALS["personas"])) $GLOBALS["personas"] = new cpersonas();
				$rsmaster = $GLOBALS["personas"]->LoadRs($sMasterFilter);
				$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
				$rsmaster->Close();
			}
			if (!$bValidMasterRecord) {
				$sRelatedRecordMsg = str_replace("%t", "personas", $Language->Phrase("RelatedRecordRequired"));
				$this->setFailureMessage($sRelatedRecordMsg);
				$rs->Close();
				return FALSE;
			}

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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;

		// Check referential integrity for master table 'personas'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_personas();
		if ($this->Dni->getSessionValue() <> "") {
			$sMasterFilter = str_replace("@Dni@", ew_AdjustSql($this->Dni->getSessionValue(), "DB"), $sMasterFilter);
		} else {
			$bValidMasterRecord = FALSE;
		}
		if ($bValidMasterRecord) {
			if (!isset($GLOBALS["personas"])) $GLOBALS["personas"] = new cpersonas();
			$rsmaster = $GLOBALS["personas"]->LoadRs($sMasterFilter);
			$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
			$rsmaster->Close();
		}
		if (!$bValidMasterRecord) {
			$sRelatedRecordMsg = str_replace("%t", "personas", $Language->Phrase("RelatedRecordRequired"));
			$this->setFailureMessage($sRelatedRecordMsg);
			return FALSE;
		}
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
			$this->Archivos_Varios->OldUploadPath = 'ArchivosLegajoPersonas';
			$this->Archivos_Varios->UploadPath = $this->Archivos_Varios->OldUploadPath;
		}
		$rsnew = array();

		// Matricula
		$this->Matricula->SetDbValueDef($rsnew, $this->Matricula->CurrentValue, NULL, FALSE);

		// Certificado_Pase
		$this->Certificado_Pase->SetDbValueDef($rsnew, $this->Certificado_Pase->CurrentValue, NULL, FALSE);

		// Tiene_DNI
		$this->Tiene_DNI->SetDbValueDef($rsnew, $this->Tiene_DNI->CurrentValue, NULL, FALSE);

		// Certificado_Medico
		$this->Certificado_Medico->SetDbValueDef($rsnew, $this->Certificado_Medico->CurrentValue, NULL, FALSE);

		// Posee_Autorizacion
		$this->Posee_Autorizacion->SetDbValueDef($rsnew, $this->Posee_Autorizacion->CurrentValue, NULL, FALSE);

		// Cooperadora
		$this->Cooperadora->SetDbValueDef($rsnew, $this->Cooperadora->CurrentValue, NULL, FALSE);

		// Fecha_Actualizacion
		$this->Fecha_Actualizacion->SetDbValueDef($rsnew, ew_CurrentDate(), NULL);
		$rsnew['Fecha_Actualizacion'] = &$this->Fecha_Actualizacion->DbValue;

		// Usuario
		$this->Usuario->SetDbValueDef($rsnew, CurrentUserName(), NULL);
		$rsnew['Usuario'] = &$this->Usuario->DbValue;

		// Dni
		if ($this->Dni->getSessionValue() <> "") {
			$rsnew['Dni'] = $this->Dni->getSessionValue();
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['Dni']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check for duplicate key
		if ($bInsertRow && $this->ValidateKey) {
			$sFilter = $this->KeyFilter();
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sKeyErrMsg = str_replace("%f", $sFilter, $Language->Phrase("DupKey"));
				$this->setFailureMessage($sKeyErrMsg);
				$rsChk->Close();
				$bInsertRow = FALSE;
			}
		}
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
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
		return $AddRow;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->Dni->AdvancedSearch->Load();
		$this->Matricula->AdvancedSearch->Load();
		$this->Certificado_Pase->AdvancedSearch->Load();
		$this->Tiene_DNI->AdvancedSearch->Load();
		$this->Certificado_Medico->AdvancedSearch->Load();
		$this->Posee_Autorizacion->AdvancedSearch->Load();
		$this->Cooperadora->AdvancedSearch->Load();
		$this->Archivos_Varios->AdvancedSearch->Load();
		$this->Fecha_Actualizacion->AdvancedSearch->Load();
		$this->Usuario->AdvancedSearch->Load();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = TRUE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = TRUE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = TRUE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_estado_actual_legajo_persona\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_estado_actual_legajo_persona',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.festado_actual_legajo_personalist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = FALSE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = TRUE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = $this->UseSelectLimit;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if (!$this->Recordset)
				$this->Recordset = $this->LoadRecordset();
			$rs = &$this->Recordset;
			if ($rs)
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetUpStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "h");
		$Doc = &$this->ExportDoc;
		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {

			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;

		}

		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
		$ParentTable = "";

		// Export master record
		if (EW_EXPORT_MASTER_RECORD && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "personas") {
			global $personas;
			if (!isset($personas)) $personas = new cpersonas;
			$rsmaster = $personas->LoadRs($this->DbMasterFilter); // Load master record
			if ($rsmaster && !$rsmaster->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("v"); // Change to vertical
				if ($this->Export <> "csv" || EW_EXPORT_MASTER_RECORD_FOR_CSV) {
					$Doc->Table = &$personas;
					$personas->ExportDocument($Doc, $rsmaster, 1, 1);
					$Doc->ExportEmptyRow();
					$Doc->Table = &$this;
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsmaster->Close();
			}
		}
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Call Page Exported server event
		$this->Page_Exported();

		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED && $this->Export <> "pdf")
			echo ew_DebugMsg();

		// Output data
		$Doc->Export();
	}

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "personas") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_Dni"] <> "") {
					$GLOBALS["personas"]->Dni->setQueryStringValue($_GET["fk_Dni"]);
					$this->Dni->setQueryStringValue($GLOBALS["personas"]->Dni->QueryStringValue);
					$this->Dni->setSessionValue($this->Dni->QueryStringValue);
					if (!is_numeric($GLOBALS["personas"]->Dni->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		} elseif (isset($_POST[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_POST[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "personas") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_Dni"] <> "") {
					$GLOBALS["personas"]->Dni->setFormValue($_POST["fk_Dni"]);
					$this->Dni->setFormValue($GLOBALS["personas"]->Dni->FormValue);
					$this->Dni->setSessionValue($this->Dni->FormValue);
					if (!is_numeric($GLOBALS["personas"]->Dni->FormValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Update URL
			$this->AddUrl = $this->AddMasterUrl($this->AddUrl);
			$this->InlineAddUrl = $this->AddMasterUrl($this->InlineAddUrl);
			$this->GridAddUrl = $this->AddMasterUrl($this->GridAddUrl);
			$this->GridEditUrl = $this->AddMasterUrl($this->GridEditUrl);

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "personas") {
				if ($this->Dni->CurrentValue == "") $this->Dni->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'estado_actual_legajo_persona';
		$usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnAdd) return;
		$table = 'estado_actual_legajo_persona';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['Dni'];

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

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		global $Language;
		if (!$this->AuditTrailOnEdit) return;
		$table = 'estado_actual_legajo_persona';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['Dni'];

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

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnDelete) return;
		$table = 'estado_actual_legajo_persona';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['Dni'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$curUser = CurrentUserID();
		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") {
					$oldvalue = $Language->Phrase("PasswordMask"); // Password Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$oldvalue = $rs[$fldname];
					else
						$oldvalue = "[MEMO]"; // Memo field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$oldvalue = "[XML]"; // XML field
				} else {
					$oldvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $curUser, "D", $table, $fldname, $key, $oldvalue, "");
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(TRUE) ?>
<?php

// Create page object
if (!isset($estado_actual_legajo_persona_list)) $estado_actual_legajo_persona_list = new cestado_actual_legajo_persona_list();

// Page init
$estado_actual_legajo_persona_list->Page_Init();

// Page main
$estado_actual_legajo_persona_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$estado_actual_legajo_persona_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($estado_actual_legajo_persona->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = festado_actual_legajo_personalist = new ew_Form("festado_actual_legajo_personalist", "list");
festado_actual_legajo_personalist.FormKeyCountName = '<?php echo $estado_actual_legajo_persona_list->FormKeyCountName ?>';

// Validate form
festado_actual_legajo_personalist.Validate = function() {
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
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	if (gridinsert && addcnt == 0) { // No row added
		ew_Alert(ewLanguage.Phrase("NoAddRecord"));
		return false;
	}
	return true;
}

// Check empty row
festado_actual_legajo_personalist.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "Matricula", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Certificado_Pase", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Tiene_DNI", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Certificado_Medico", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Posee_Autorizacion", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Cooperadora", false)) return false;
	return true;
}

// Form_CustomValidate event
festado_actual_legajo_personalist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
festado_actual_legajo_personalist.ValidateRequired = true;
<?php } else { ?>
festado_actual_legajo_personalist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
festado_actual_legajo_personalist.Lists["x_Matricula"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
festado_actual_legajo_personalist.Lists["x_Matricula"].Options = <?php echo json_encode($estado_actual_legajo_persona->Matricula->Options()) ?>;
festado_actual_legajo_personalist.Lists["x_Certificado_Pase"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
festado_actual_legajo_personalist.Lists["x_Certificado_Pase"].Options = <?php echo json_encode($estado_actual_legajo_persona->Certificado_Pase->Options()) ?>;
festado_actual_legajo_personalist.Lists["x_Tiene_DNI"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
festado_actual_legajo_personalist.Lists["x_Tiene_DNI"].Options = <?php echo json_encode($estado_actual_legajo_persona->Tiene_DNI->Options()) ?>;
festado_actual_legajo_personalist.Lists["x_Certificado_Medico"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
festado_actual_legajo_personalist.Lists["x_Certificado_Medico"].Options = <?php echo json_encode($estado_actual_legajo_persona->Certificado_Medico->Options()) ?>;
festado_actual_legajo_personalist.Lists["x_Posee_Autorizacion"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
festado_actual_legajo_personalist.Lists["x_Posee_Autorizacion"].Options = <?php echo json_encode($estado_actual_legajo_persona->Posee_Autorizacion->Options()) ?>;
festado_actual_legajo_personalist.Lists["x_Cooperadora"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
festado_actual_legajo_personalist.Lists["x_Cooperadora"].Options = <?php echo json_encode($estado_actual_legajo_persona->Cooperadora->Options()) ?>;

// Form object for search
var CurrentSearchForm = festado_actual_legajo_personalistsrch = new ew_Form("festado_actual_legajo_personalistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($estado_actual_legajo_persona->Export == "") { ?>
<div class="ewToolbar">
<?php if ($estado_actual_legajo_persona->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($estado_actual_legajo_persona_list->TotalRecs > 0 && $estado_actual_legajo_persona_list->ExportOptions->Visible()) { ?>
<?php $estado_actual_legajo_persona_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($estado_actual_legajo_persona_list->SearchOptions->Visible()) { ?>
<?php $estado_actual_legajo_persona_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($estado_actual_legajo_persona_list->FilterOptions->Visible()) { ?>
<?php $estado_actual_legajo_persona_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php if ($estado_actual_legajo_persona->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php if (($estado_actual_legajo_persona->Export == "") || (EW_EXPORT_MASTER_RECORD && $estado_actual_legajo_persona->Export == "print")) { ?>
<?php
if ($estado_actual_legajo_persona_list->DbMasterFilter <> "" && $estado_actual_legajo_persona->getCurrentMasterTable() == "personas") {
	if ($estado_actual_legajo_persona_list->MasterRecordExists) {
?>
<?php include_once "personasmaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
if ($estado_actual_legajo_persona->CurrentAction == "gridadd") {
	$estado_actual_legajo_persona->CurrentFilter = "0=1";
	$estado_actual_legajo_persona_list->StartRec = 1;
	$estado_actual_legajo_persona_list->DisplayRecs = $estado_actual_legajo_persona->GridAddRowCount;
	$estado_actual_legajo_persona_list->TotalRecs = $estado_actual_legajo_persona_list->DisplayRecs;
	$estado_actual_legajo_persona_list->StopRec = $estado_actual_legajo_persona_list->DisplayRecs;
} else {
	$bSelectLimit = $estado_actual_legajo_persona_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($estado_actual_legajo_persona_list->TotalRecs <= 0)
			$estado_actual_legajo_persona_list->TotalRecs = $estado_actual_legajo_persona->SelectRecordCount();
	} else {
		if (!$estado_actual_legajo_persona_list->Recordset && ($estado_actual_legajo_persona_list->Recordset = $estado_actual_legajo_persona_list->LoadRecordset()))
			$estado_actual_legajo_persona_list->TotalRecs = $estado_actual_legajo_persona_list->Recordset->RecordCount();
	}
	$estado_actual_legajo_persona_list->StartRec = 1;
	if ($estado_actual_legajo_persona_list->DisplayRecs <= 0 || ($estado_actual_legajo_persona->Export <> "" && $estado_actual_legajo_persona->ExportAll)) // Display all records
		$estado_actual_legajo_persona_list->DisplayRecs = $estado_actual_legajo_persona_list->TotalRecs;
	if (!($estado_actual_legajo_persona->Export <> "" && $estado_actual_legajo_persona->ExportAll))
		$estado_actual_legajo_persona_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$estado_actual_legajo_persona_list->Recordset = $estado_actual_legajo_persona_list->LoadRecordset($estado_actual_legajo_persona_list->StartRec-1, $estado_actual_legajo_persona_list->DisplayRecs);

	// Set no record found message
	if ($estado_actual_legajo_persona->CurrentAction == "" && $estado_actual_legajo_persona_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$estado_actual_legajo_persona_list->setWarningMessage(ew_DeniedMsg());
		if ($estado_actual_legajo_persona_list->SearchWhere == "0=101")
			$estado_actual_legajo_persona_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$estado_actual_legajo_persona_list->setWarningMessage($Language->Phrase("NoRecord"));
	}

	// Audit trail on search
	if ($estado_actual_legajo_persona_list->AuditTrailOnSearch && $estado_actual_legajo_persona_list->Command == "search" && !$estado_actual_legajo_persona_list->RestoreSearch) {
		$searchparm = ew_ServerVar("QUERY_STRING");
		$searchsql = $estado_actual_legajo_persona_list->getSessionWhere();
		$estado_actual_legajo_persona_list->WriteAuditTrailOnSearch($searchparm, $searchsql);
	}
}
$estado_actual_legajo_persona_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($estado_actual_legajo_persona->Export == "" && $estado_actual_legajo_persona->CurrentAction == "") { ?>
<form name="festado_actual_legajo_personalistsrch" id="festado_actual_legajo_personalistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($estado_actual_legajo_persona_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="festado_actual_legajo_personalistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="estado_actual_legajo_persona">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($estado_actual_legajo_persona_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($estado_actual_legajo_persona_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $estado_actual_legajo_persona_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($estado_actual_legajo_persona_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($estado_actual_legajo_persona_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($estado_actual_legajo_persona_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($estado_actual_legajo_persona_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $estado_actual_legajo_persona_list->ShowPageHeader(); ?>
<?php
$estado_actual_legajo_persona_list->ShowMessage();
?>
<?php if ($estado_actual_legajo_persona_list->TotalRecs > 0 || $estado_actual_legajo_persona->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid estado_actual_legajo_persona">
<?php if ($estado_actual_legajo_persona->Export == "") { ?>
<div class="panel-heading ewGridUpperPanel">
<?php if ($estado_actual_legajo_persona->CurrentAction <> "gridadd" && $estado_actual_legajo_persona->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($estado_actual_legajo_persona_list->Pager)) $estado_actual_legajo_persona_list->Pager = new cPrevNextPager($estado_actual_legajo_persona_list->StartRec, $estado_actual_legajo_persona_list->DisplayRecs, $estado_actual_legajo_persona_list->TotalRecs) ?>
<?php if ($estado_actual_legajo_persona_list->Pager->RecordCount > 0 && $estado_actual_legajo_persona_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($estado_actual_legajo_persona_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $estado_actual_legajo_persona_list->PageUrl() ?>start=<?php echo $estado_actual_legajo_persona_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($estado_actual_legajo_persona_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $estado_actual_legajo_persona_list->PageUrl() ?>start=<?php echo $estado_actual_legajo_persona_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $estado_actual_legajo_persona_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($estado_actual_legajo_persona_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $estado_actual_legajo_persona_list->PageUrl() ?>start=<?php echo $estado_actual_legajo_persona_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($estado_actual_legajo_persona_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $estado_actual_legajo_persona_list->PageUrl() ?>start=<?php echo $estado_actual_legajo_persona_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $estado_actual_legajo_persona_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $estado_actual_legajo_persona_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $estado_actual_legajo_persona_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $estado_actual_legajo_persona_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($estado_actual_legajo_persona_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="festado_actual_legajo_personalist" id="festado_actual_legajo_personalist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($estado_actual_legajo_persona_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $estado_actual_legajo_persona_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="estado_actual_legajo_persona">
<?php if ($estado_actual_legajo_persona->getCurrentMasterTable() == "personas" && $estado_actual_legajo_persona->CurrentAction <> "") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="personas">
<input type="hidden" name="fk_Dni" value="<?php echo $estado_actual_legajo_persona->Dni->getSessionValue() ?>">
<?php } ?>
<div id="gmp_estado_actual_legajo_persona" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($estado_actual_legajo_persona_list->TotalRecs > 0 || $estado_actual_legajo_persona->CurrentAction == "add" || $estado_actual_legajo_persona->CurrentAction == "copy") { ?>
<table id="tbl_estado_actual_legajo_personalist" class="table ewTable">
<?php echo $estado_actual_legajo_persona->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$estado_actual_legajo_persona_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$estado_actual_legajo_persona_list->RenderListOptions();

// Render list options (header, left)
$estado_actual_legajo_persona_list->ListOptions->Render("header", "left");
?>
<?php if ($estado_actual_legajo_persona->Matricula->Visible) { // Matricula ?>
	<?php if ($estado_actual_legajo_persona->SortUrl($estado_actual_legajo_persona->Matricula) == "") { ?>
		<th data-name="Matricula"><div id="elh_estado_actual_legajo_persona_Matricula" class="estado_actual_legajo_persona_Matricula"><div class="ewTableHeaderCaption"><?php echo $estado_actual_legajo_persona->Matricula->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Matricula"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estado_actual_legajo_persona->SortUrl($estado_actual_legajo_persona->Matricula) ?>',1);"><div id="elh_estado_actual_legajo_persona_Matricula" class="estado_actual_legajo_persona_Matricula">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estado_actual_legajo_persona->Matricula->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estado_actual_legajo_persona->Matricula->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estado_actual_legajo_persona->Matricula->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($estado_actual_legajo_persona->Certificado_Pase->Visible) { // Certificado_Pase ?>
	<?php if ($estado_actual_legajo_persona->SortUrl($estado_actual_legajo_persona->Certificado_Pase) == "") { ?>
		<th data-name="Certificado_Pase"><div id="elh_estado_actual_legajo_persona_Certificado_Pase" class="estado_actual_legajo_persona_Certificado_Pase"><div class="ewTableHeaderCaption"><?php echo $estado_actual_legajo_persona->Certificado_Pase->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Certificado_Pase"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estado_actual_legajo_persona->SortUrl($estado_actual_legajo_persona->Certificado_Pase) ?>',1);"><div id="elh_estado_actual_legajo_persona_Certificado_Pase" class="estado_actual_legajo_persona_Certificado_Pase">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estado_actual_legajo_persona->Certificado_Pase->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estado_actual_legajo_persona->Certificado_Pase->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estado_actual_legajo_persona->Certificado_Pase->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($estado_actual_legajo_persona->Tiene_DNI->Visible) { // Tiene_DNI ?>
	<?php if ($estado_actual_legajo_persona->SortUrl($estado_actual_legajo_persona->Tiene_DNI) == "") { ?>
		<th data-name="Tiene_DNI"><div id="elh_estado_actual_legajo_persona_Tiene_DNI" class="estado_actual_legajo_persona_Tiene_DNI"><div class="ewTableHeaderCaption"><?php echo $estado_actual_legajo_persona->Tiene_DNI->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Tiene_DNI"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estado_actual_legajo_persona->SortUrl($estado_actual_legajo_persona->Tiene_DNI) ?>',1);"><div id="elh_estado_actual_legajo_persona_Tiene_DNI" class="estado_actual_legajo_persona_Tiene_DNI">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estado_actual_legajo_persona->Tiene_DNI->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estado_actual_legajo_persona->Tiene_DNI->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estado_actual_legajo_persona->Tiene_DNI->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($estado_actual_legajo_persona->Certificado_Medico->Visible) { // Certificado_Medico ?>
	<?php if ($estado_actual_legajo_persona->SortUrl($estado_actual_legajo_persona->Certificado_Medico) == "") { ?>
		<th data-name="Certificado_Medico"><div id="elh_estado_actual_legajo_persona_Certificado_Medico" class="estado_actual_legajo_persona_Certificado_Medico"><div class="ewTableHeaderCaption"><?php echo $estado_actual_legajo_persona->Certificado_Medico->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Certificado_Medico"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estado_actual_legajo_persona->SortUrl($estado_actual_legajo_persona->Certificado_Medico) ?>',1);"><div id="elh_estado_actual_legajo_persona_Certificado_Medico" class="estado_actual_legajo_persona_Certificado_Medico">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estado_actual_legajo_persona->Certificado_Medico->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estado_actual_legajo_persona->Certificado_Medico->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estado_actual_legajo_persona->Certificado_Medico->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($estado_actual_legajo_persona->Posee_Autorizacion->Visible) { // Posee_Autorizacion ?>
	<?php if ($estado_actual_legajo_persona->SortUrl($estado_actual_legajo_persona->Posee_Autorizacion) == "") { ?>
		<th data-name="Posee_Autorizacion"><div id="elh_estado_actual_legajo_persona_Posee_Autorizacion" class="estado_actual_legajo_persona_Posee_Autorizacion"><div class="ewTableHeaderCaption"><?php echo $estado_actual_legajo_persona->Posee_Autorizacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Posee_Autorizacion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estado_actual_legajo_persona->SortUrl($estado_actual_legajo_persona->Posee_Autorizacion) ?>',1);"><div id="elh_estado_actual_legajo_persona_Posee_Autorizacion" class="estado_actual_legajo_persona_Posee_Autorizacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estado_actual_legajo_persona->Posee_Autorizacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estado_actual_legajo_persona->Posee_Autorizacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estado_actual_legajo_persona->Posee_Autorizacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($estado_actual_legajo_persona->Cooperadora->Visible) { // Cooperadora ?>
	<?php if ($estado_actual_legajo_persona->SortUrl($estado_actual_legajo_persona->Cooperadora) == "") { ?>
		<th data-name="Cooperadora"><div id="elh_estado_actual_legajo_persona_Cooperadora" class="estado_actual_legajo_persona_Cooperadora"><div class="ewTableHeaderCaption"><?php echo $estado_actual_legajo_persona->Cooperadora->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Cooperadora"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estado_actual_legajo_persona->SortUrl($estado_actual_legajo_persona->Cooperadora) ?>',1);"><div id="elh_estado_actual_legajo_persona_Cooperadora" class="estado_actual_legajo_persona_Cooperadora">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estado_actual_legajo_persona->Cooperadora->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estado_actual_legajo_persona->Cooperadora->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estado_actual_legajo_persona->Cooperadora->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($estado_actual_legajo_persona->Fecha_Actualizacion->Visible) { // Fecha_Actualizacion ?>
	<?php if ($estado_actual_legajo_persona->SortUrl($estado_actual_legajo_persona->Fecha_Actualizacion) == "") { ?>
		<th data-name="Fecha_Actualizacion"><div id="elh_estado_actual_legajo_persona_Fecha_Actualizacion" class="estado_actual_legajo_persona_Fecha_Actualizacion"><div class="ewTableHeaderCaption"><?php echo $estado_actual_legajo_persona->Fecha_Actualizacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Fecha_Actualizacion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estado_actual_legajo_persona->SortUrl($estado_actual_legajo_persona->Fecha_Actualizacion) ?>',1);"><div id="elh_estado_actual_legajo_persona_Fecha_Actualizacion" class="estado_actual_legajo_persona_Fecha_Actualizacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estado_actual_legajo_persona->Fecha_Actualizacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estado_actual_legajo_persona->Fecha_Actualizacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estado_actual_legajo_persona->Fecha_Actualizacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($estado_actual_legajo_persona->Usuario->Visible) { // Usuario ?>
	<?php if ($estado_actual_legajo_persona->SortUrl($estado_actual_legajo_persona->Usuario) == "") { ?>
		<th data-name="Usuario"><div id="elh_estado_actual_legajo_persona_Usuario" class="estado_actual_legajo_persona_Usuario"><div class="ewTableHeaderCaption"><?php echo $estado_actual_legajo_persona->Usuario->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Usuario"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $estado_actual_legajo_persona->SortUrl($estado_actual_legajo_persona->Usuario) ?>',1);"><div id="elh_estado_actual_legajo_persona_Usuario" class="estado_actual_legajo_persona_Usuario">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estado_actual_legajo_persona->Usuario->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($estado_actual_legajo_persona->Usuario->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estado_actual_legajo_persona->Usuario->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$estado_actual_legajo_persona_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
	if ($estado_actual_legajo_persona->CurrentAction == "add" || $estado_actual_legajo_persona->CurrentAction == "copy") {
		$estado_actual_legajo_persona_list->RowIndex = 0;
		$estado_actual_legajo_persona_list->KeyCount = $estado_actual_legajo_persona_list->RowIndex;
		if ($estado_actual_legajo_persona->CurrentAction == "add")
			$estado_actual_legajo_persona_list->LoadDefaultValues();
		if ($estado_actual_legajo_persona->EventCancelled) // Insert failed
			$estado_actual_legajo_persona_list->RestoreFormValues(); // Restore form values

		// Set row properties
		$estado_actual_legajo_persona->ResetAttrs();
		$estado_actual_legajo_persona->RowAttrs = array_merge($estado_actual_legajo_persona->RowAttrs, array('data-rowindex'=>0, 'id'=>'r0_estado_actual_legajo_persona', 'data-rowtype'=>EW_ROWTYPE_ADD));
		$estado_actual_legajo_persona->RowType = EW_ROWTYPE_ADD;

		// Render row
		$estado_actual_legajo_persona_list->RenderRow();

		// Render list options
		$estado_actual_legajo_persona_list->RenderListOptions();
		$estado_actual_legajo_persona_list->StartRowCnt = 0;
?>
	<tr<?php echo $estado_actual_legajo_persona->RowAttributes() ?>>
<?php

// Render list options (body, left)
$estado_actual_legajo_persona_list->ListOptions->Render("body", "left", $estado_actual_legajo_persona_list->RowCnt);
?>
	<?php if ($estado_actual_legajo_persona->Matricula->Visible) { // Matricula ?>
		<td data-name="Matricula">
<span id="el<?php echo $estado_actual_legajo_persona_list->RowCnt ?>_estado_actual_legajo_persona_Matricula" class="form-group estado_actual_legajo_persona_Matricula">
<div id="tp_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Matricula" class="ewTemplate"><input type="radio" data-table="estado_actual_legajo_persona" data-field="x_Matricula" data-value-separator="<?php echo $estado_actual_legajo_persona->Matricula->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Matricula" id="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Matricula" value="{value}"<?php echo $estado_actual_legajo_persona->Matricula->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Matricula" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $estado_actual_legajo_persona->Matricula->RadioButtonListHtml(FALSE, "x{$estado_actual_legajo_persona_list->RowIndex}_Matricula") ?>
</div></div>
</span>
<input type="hidden" data-table="estado_actual_legajo_persona" data-field="x_Matricula" name="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Matricula" id="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Matricula" value="<?php echo ew_HtmlEncode($estado_actual_legajo_persona->Matricula->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($estado_actual_legajo_persona->Certificado_Pase->Visible) { // Certificado_Pase ?>
		<td data-name="Certificado_Pase">
<span id="el<?php echo $estado_actual_legajo_persona_list->RowCnt ?>_estado_actual_legajo_persona_Certificado_Pase" class="form-group estado_actual_legajo_persona_Certificado_Pase">
<div id="tp_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Pase" class="ewTemplate"><input type="radio" data-table="estado_actual_legajo_persona" data-field="x_Certificado_Pase" data-value-separator="<?php echo $estado_actual_legajo_persona->Certificado_Pase->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Pase" id="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Pase" value="{value}"<?php echo $estado_actual_legajo_persona->Certificado_Pase->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Pase" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $estado_actual_legajo_persona->Certificado_Pase->RadioButtonListHtml(FALSE, "x{$estado_actual_legajo_persona_list->RowIndex}_Certificado_Pase") ?>
</div></div>
</span>
<input type="hidden" data-table="estado_actual_legajo_persona" data-field="x_Certificado_Pase" name="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Pase" id="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Pase" value="<?php echo ew_HtmlEncode($estado_actual_legajo_persona->Certificado_Pase->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($estado_actual_legajo_persona->Tiene_DNI->Visible) { // Tiene_DNI ?>
		<td data-name="Tiene_DNI">
<span id="el<?php echo $estado_actual_legajo_persona_list->RowCnt ?>_estado_actual_legajo_persona_Tiene_DNI" class="form-group estado_actual_legajo_persona_Tiene_DNI">
<div id="tp_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Tiene_DNI" class="ewTemplate"><input type="radio" data-table="estado_actual_legajo_persona" data-field="x_Tiene_DNI" data-value-separator="<?php echo $estado_actual_legajo_persona->Tiene_DNI->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Tiene_DNI" id="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Tiene_DNI" value="{value}"<?php echo $estado_actual_legajo_persona->Tiene_DNI->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Tiene_DNI" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $estado_actual_legajo_persona->Tiene_DNI->RadioButtonListHtml(FALSE, "x{$estado_actual_legajo_persona_list->RowIndex}_Tiene_DNI") ?>
</div></div>
</span>
<input type="hidden" data-table="estado_actual_legajo_persona" data-field="x_Tiene_DNI" name="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Tiene_DNI" id="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Tiene_DNI" value="<?php echo ew_HtmlEncode($estado_actual_legajo_persona->Tiene_DNI->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($estado_actual_legajo_persona->Certificado_Medico->Visible) { // Certificado_Medico ?>
		<td data-name="Certificado_Medico">
<span id="el<?php echo $estado_actual_legajo_persona_list->RowCnt ?>_estado_actual_legajo_persona_Certificado_Medico" class="form-group estado_actual_legajo_persona_Certificado_Medico">
<div id="tp_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Medico" class="ewTemplate"><input type="radio" data-table="estado_actual_legajo_persona" data-field="x_Certificado_Medico" data-value-separator="<?php echo $estado_actual_legajo_persona->Certificado_Medico->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Medico" id="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Medico" value="{value}"<?php echo $estado_actual_legajo_persona->Certificado_Medico->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Medico" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $estado_actual_legajo_persona->Certificado_Medico->RadioButtonListHtml(FALSE, "x{$estado_actual_legajo_persona_list->RowIndex}_Certificado_Medico") ?>
</div></div>
</span>
<input type="hidden" data-table="estado_actual_legajo_persona" data-field="x_Certificado_Medico" name="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Medico" id="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Medico" value="<?php echo ew_HtmlEncode($estado_actual_legajo_persona->Certificado_Medico->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($estado_actual_legajo_persona->Posee_Autorizacion->Visible) { // Posee_Autorizacion ?>
		<td data-name="Posee_Autorizacion">
<span id="el<?php echo $estado_actual_legajo_persona_list->RowCnt ?>_estado_actual_legajo_persona_Posee_Autorizacion" class="form-group estado_actual_legajo_persona_Posee_Autorizacion">
<div id="tp_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Posee_Autorizacion" class="ewTemplate"><input type="radio" data-table="estado_actual_legajo_persona" data-field="x_Posee_Autorizacion" data-value-separator="<?php echo $estado_actual_legajo_persona->Posee_Autorizacion->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Posee_Autorizacion" id="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Posee_Autorizacion" value="{value}"<?php echo $estado_actual_legajo_persona->Posee_Autorizacion->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Posee_Autorizacion" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $estado_actual_legajo_persona->Posee_Autorizacion->RadioButtonListHtml(FALSE, "x{$estado_actual_legajo_persona_list->RowIndex}_Posee_Autorizacion") ?>
</div></div>
</span>
<input type="hidden" data-table="estado_actual_legajo_persona" data-field="x_Posee_Autorizacion" name="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Posee_Autorizacion" id="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Posee_Autorizacion" value="<?php echo ew_HtmlEncode($estado_actual_legajo_persona->Posee_Autorizacion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($estado_actual_legajo_persona->Cooperadora->Visible) { // Cooperadora ?>
		<td data-name="Cooperadora">
<span id="el<?php echo $estado_actual_legajo_persona_list->RowCnt ?>_estado_actual_legajo_persona_Cooperadora" class="form-group estado_actual_legajo_persona_Cooperadora">
<div id="tp_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Cooperadora" class="ewTemplate"><input type="radio" data-table="estado_actual_legajo_persona" data-field="x_Cooperadora" data-value-separator="<?php echo $estado_actual_legajo_persona->Cooperadora->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Cooperadora" id="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Cooperadora" value="{value}"<?php echo $estado_actual_legajo_persona->Cooperadora->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Cooperadora" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $estado_actual_legajo_persona->Cooperadora->RadioButtonListHtml(FALSE, "x{$estado_actual_legajo_persona_list->RowIndex}_Cooperadora") ?>
</div></div>
</span>
<input type="hidden" data-table="estado_actual_legajo_persona" data-field="x_Cooperadora" name="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Cooperadora" id="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Cooperadora" value="<?php echo ew_HtmlEncode($estado_actual_legajo_persona->Cooperadora->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($estado_actual_legajo_persona->Fecha_Actualizacion->Visible) { // Fecha_Actualizacion ?>
		<td data-name="Fecha_Actualizacion">
<input type="hidden" data-table="estado_actual_legajo_persona" data-field="x_Fecha_Actualizacion" name="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Fecha_Actualizacion" id="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Fecha_Actualizacion" value="<?php echo ew_HtmlEncode($estado_actual_legajo_persona->Fecha_Actualizacion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($estado_actual_legajo_persona->Usuario->Visible) { // Usuario ?>
		<td data-name="Usuario">
<input type="hidden" data-table="estado_actual_legajo_persona" data-field="x_Usuario" name="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Usuario" id="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Usuario" value="<?php echo ew_HtmlEncode($estado_actual_legajo_persona->Usuario->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$estado_actual_legajo_persona_list->ListOptions->Render("body", "right", $estado_actual_legajo_persona_list->RowCnt);
?>
<script type="text/javascript">
festado_actual_legajo_personalist.UpdateOpts(<?php echo $estado_actual_legajo_persona_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
<?php
if ($estado_actual_legajo_persona->ExportAll && $estado_actual_legajo_persona->Export <> "") {
	$estado_actual_legajo_persona_list->StopRec = $estado_actual_legajo_persona_list->TotalRecs;
} else {

	// Set the last record to display
	if ($estado_actual_legajo_persona_list->TotalRecs > $estado_actual_legajo_persona_list->StartRec + $estado_actual_legajo_persona_list->DisplayRecs - 1)
		$estado_actual_legajo_persona_list->StopRec = $estado_actual_legajo_persona_list->StartRec + $estado_actual_legajo_persona_list->DisplayRecs - 1;
	else
		$estado_actual_legajo_persona_list->StopRec = $estado_actual_legajo_persona_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($estado_actual_legajo_persona_list->FormKeyCountName) && ($estado_actual_legajo_persona->CurrentAction == "gridadd" || $estado_actual_legajo_persona->CurrentAction == "gridedit" || $estado_actual_legajo_persona->CurrentAction == "F")) {
		$estado_actual_legajo_persona_list->KeyCount = $objForm->GetValue($estado_actual_legajo_persona_list->FormKeyCountName);
		$estado_actual_legajo_persona_list->StopRec = $estado_actual_legajo_persona_list->StartRec + $estado_actual_legajo_persona_list->KeyCount - 1;
	}
}
$estado_actual_legajo_persona_list->RecCnt = $estado_actual_legajo_persona_list->StartRec - 1;
if ($estado_actual_legajo_persona_list->Recordset && !$estado_actual_legajo_persona_list->Recordset->EOF) {
	$estado_actual_legajo_persona_list->Recordset->MoveFirst();
	$bSelectLimit = $estado_actual_legajo_persona_list->UseSelectLimit;
	if (!$bSelectLimit && $estado_actual_legajo_persona_list->StartRec > 1)
		$estado_actual_legajo_persona_list->Recordset->Move($estado_actual_legajo_persona_list->StartRec - 1);
} elseif (!$estado_actual_legajo_persona->AllowAddDeleteRow && $estado_actual_legajo_persona_list->StopRec == 0) {
	$estado_actual_legajo_persona_list->StopRec = $estado_actual_legajo_persona->GridAddRowCount;
}

// Initialize aggregate
$estado_actual_legajo_persona->RowType = EW_ROWTYPE_AGGREGATEINIT;
$estado_actual_legajo_persona->ResetAttrs();
$estado_actual_legajo_persona_list->RenderRow();
$estado_actual_legajo_persona_list->EditRowCnt = 0;
if ($estado_actual_legajo_persona->CurrentAction == "edit")
	$estado_actual_legajo_persona_list->RowIndex = 1;
if ($estado_actual_legajo_persona->CurrentAction == "gridadd")
	$estado_actual_legajo_persona_list->RowIndex = 0;
if ($estado_actual_legajo_persona->CurrentAction == "gridedit")
	$estado_actual_legajo_persona_list->RowIndex = 0;
while ($estado_actual_legajo_persona_list->RecCnt < $estado_actual_legajo_persona_list->StopRec) {
	$estado_actual_legajo_persona_list->RecCnt++;
	if (intval($estado_actual_legajo_persona_list->RecCnt) >= intval($estado_actual_legajo_persona_list->StartRec)) {
		$estado_actual_legajo_persona_list->RowCnt++;
		if ($estado_actual_legajo_persona->CurrentAction == "gridadd" || $estado_actual_legajo_persona->CurrentAction == "gridedit" || $estado_actual_legajo_persona->CurrentAction == "F") {
			$estado_actual_legajo_persona_list->RowIndex++;
			$objForm->Index = $estado_actual_legajo_persona_list->RowIndex;
			if ($objForm->HasValue($estado_actual_legajo_persona_list->FormActionName))
				$estado_actual_legajo_persona_list->RowAction = strval($objForm->GetValue($estado_actual_legajo_persona_list->FormActionName));
			elseif ($estado_actual_legajo_persona->CurrentAction == "gridadd")
				$estado_actual_legajo_persona_list->RowAction = "insert";
			else
				$estado_actual_legajo_persona_list->RowAction = "";
		}

		// Set up key count
		$estado_actual_legajo_persona_list->KeyCount = $estado_actual_legajo_persona_list->RowIndex;

		// Init row class and style
		$estado_actual_legajo_persona->ResetAttrs();
		$estado_actual_legajo_persona->CssClass = "";
		if ($estado_actual_legajo_persona->CurrentAction == "gridadd") {
			$estado_actual_legajo_persona_list->LoadDefaultValues(); // Load default values
		} else {
			$estado_actual_legajo_persona_list->LoadRowValues($estado_actual_legajo_persona_list->Recordset); // Load row values
		}
		$estado_actual_legajo_persona->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($estado_actual_legajo_persona->CurrentAction == "gridadd") // Grid add
			$estado_actual_legajo_persona->RowType = EW_ROWTYPE_ADD; // Render add
		if ($estado_actual_legajo_persona->CurrentAction == "gridadd" && $estado_actual_legajo_persona->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$estado_actual_legajo_persona_list->RestoreCurrentRowFormValues($estado_actual_legajo_persona_list->RowIndex); // Restore form values
		if ($estado_actual_legajo_persona->CurrentAction == "edit") {
			if ($estado_actual_legajo_persona_list->CheckInlineEditKey() && $estado_actual_legajo_persona_list->EditRowCnt == 0) { // Inline edit
				$estado_actual_legajo_persona->RowType = EW_ROWTYPE_EDIT; // Render edit
			}
		}
		if ($estado_actual_legajo_persona->CurrentAction == "gridedit") { // Grid edit
			if ($estado_actual_legajo_persona->EventCancelled) {
				$estado_actual_legajo_persona_list->RestoreCurrentRowFormValues($estado_actual_legajo_persona_list->RowIndex); // Restore form values
			}
			if ($estado_actual_legajo_persona_list->RowAction == "insert")
				$estado_actual_legajo_persona->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$estado_actual_legajo_persona->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($estado_actual_legajo_persona->CurrentAction == "edit" && $estado_actual_legajo_persona->RowType == EW_ROWTYPE_EDIT && $estado_actual_legajo_persona->EventCancelled) { // Update failed
			$objForm->Index = 1;
			$estado_actual_legajo_persona_list->RestoreFormValues(); // Restore form values
		}
		if ($estado_actual_legajo_persona->CurrentAction == "gridedit" && ($estado_actual_legajo_persona->RowType == EW_ROWTYPE_EDIT || $estado_actual_legajo_persona->RowType == EW_ROWTYPE_ADD) && $estado_actual_legajo_persona->EventCancelled) // Update failed
			$estado_actual_legajo_persona_list->RestoreCurrentRowFormValues($estado_actual_legajo_persona_list->RowIndex); // Restore form values
		if ($estado_actual_legajo_persona->RowType == EW_ROWTYPE_EDIT) // Edit row
			$estado_actual_legajo_persona_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$estado_actual_legajo_persona->RowAttrs = array_merge($estado_actual_legajo_persona->RowAttrs, array('data-rowindex'=>$estado_actual_legajo_persona_list->RowCnt, 'id'=>'r' . $estado_actual_legajo_persona_list->RowCnt . '_estado_actual_legajo_persona', 'data-rowtype'=>$estado_actual_legajo_persona->RowType));

		// Render row
		$estado_actual_legajo_persona_list->RenderRow();

		// Render list options
		$estado_actual_legajo_persona_list->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($estado_actual_legajo_persona_list->RowAction <> "delete" && $estado_actual_legajo_persona_list->RowAction <> "insertdelete" && !($estado_actual_legajo_persona_list->RowAction == "insert" && $estado_actual_legajo_persona->CurrentAction == "F" && $estado_actual_legajo_persona_list->EmptyRow())) {
?>
	<tr<?php echo $estado_actual_legajo_persona->RowAttributes() ?>>
<?php

// Render list options (body, left)
$estado_actual_legajo_persona_list->ListOptions->Render("body", "left", $estado_actual_legajo_persona_list->RowCnt);
?>
	<?php if ($estado_actual_legajo_persona->Matricula->Visible) { // Matricula ?>
		<td data-name="Matricula"<?php echo $estado_actual_legajo_persona->Matricula->CellAttributes() ?>>
<?php if ($estado_actual_legajo_persona->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $estado_actual_legajo_persona_list->RowCnt ?>_estado_actual_legajo_persona_Matricula" class="form-group estado_actual_legajo_persona_Matricula">
<div id="tp_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Matricula" class="ewTemplate"><input type="radio" data-table="estado_actual_legajo_persona" data-field="x_Matricula" data-value-separator="<?php echo $estado_actual_legajo_persona->Matricula->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Matricula" id="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Matricula" value="{value}"<?php echo $estado_actual_legajo_persona->Matricula->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Matricula" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $estado_actual_legajo_persona->Matricula->RadioButtonListHtml(FALSE, "x{$estado_actual_legajo_persona_list->RowIndex}_Matricula") ?>
</div></div>
</span>
<input type="hidden" data-table="estado_actual_legajo_persona" data-field="x_Matricula" name="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Matricula" id="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Matricula" value="<?php echo ew_HtmlEncode($estado_actual_legajo_persona->Matricula->OldValue) ?>">
<?php } ?>
<?php if ($estado_actual_legajo_persona->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $estado_actual_legajo_persona_list->RowCnt ?>_estado_actual_legajo_persona_Matricula" class="form-group estado_actual_legajo_persona_Matricula">
<div id="tp_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Matricula" class="ewTemplate"><input type="radio" data-table="estado_actual_legajo_persona" data-field="x_Matricula" data-value-separator="<?php echo $estado_actual_legajo_persona->Matricula->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Matricula" id="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Matricula" value="{value}"<?php echo $estado_actual_legajo_persona->Matricula->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Matricula" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $estado_actual_legajo_persona->Matricula->RadioButtonListHtml(FALSE, "x{$estado_actual_legajo_persona_list->RowIndex}_Matricula") ?>
</div></div>
</span>
<?php } ?>
<?php if ($estado_actual_legajo_persona->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $estado_actual_legajo_persona_list->RowCnt ?>_estado_actual_legajo_persona_Matricula" class="estado_actual_legajo_persona_Matricula">
<span<?php echo $estado_actual_legajo_persona->Matricula->ViewAttributes() ?>>
<?php echo $estado_actual_legajo_persona->Matricula->ListViewValue() ?></span>
</span>
<?php } ?>
<a id="<?php echo $estado_actual_legajo_persona_list->PageObjName . "_row_" . $estado_actual_legajo_persona_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($estado_actual_legajo_persona->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="estado_actual_legajo_persona" data-field="x_Dni" name="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Dni" id="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Dni" value="<?php echo ew_HtmlEncode($estado_actual_legajo_persona->Dni->CurrentValue) ?>">
<input type="hidden" data-table="estado_actual_legajo_persona" data-field="x_Dni" name="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Dni" id="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Dni" value="<?php echo ew_HtmlEncode($estado_actual_legajo_persona->Dni->OldValue) ?>">
<?php } ?>
<?php if ($estado_actual_legajo_persona->RowType == EW_ROWTYPE_EDIT || $estado_actual_legajo_persona->CurrentMode == "edit") { ?>
<input type="hidden" data-table="estado_actual_legajo_persona" data-field="x_Dni" name="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Dni" id="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Dni" value="<?php echo ew_HtmlEncode($estado_actual_legajo_persona->Dni->CurrentValue) ?>">
<?php } ?>
	<?php if ($estado_actual_legajo_persona->Certificado_Pase->Visible) { // Certificado_Pase ?>
		<td data-name="Certificado_Pase"<?php echo $estado_actual_legajo_persona->Certificado_Pase->CellAttributes() ?>>
<?php if ($estado_actual_legajo_persona->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $estado_actual_legajo_persona_list->RowCnt ?>_estado_actual_legajo_persona_Certificado_Pase" class="form-group estado_actual_legajo_persona_Certificado_Pase">
<div id="tp_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Pase" class="ewTemplate"><input type="radio" data-table="estado_actual_legajo_persona" data-field="x_Certificado_Pase" data-value-separator="<?php echo $estado_actual_legajo_persona->Certificado_Pase->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Pase" id="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Pase" value="{value}"<?php echo $estado_actual_legajo_persona->Certificado_Pase->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Pase" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $estado_actual_legajo_persona->Certificado_Pase->RadioButtonListHtml(FALSE, "x{$estado_actual_legajo_persona_list->RowIndex}_Certificado_Pase") ?>
</div></div>
</span>
<input type="hidden" data-table="estado_actual_legajo_persona" data-field="x_Certificado_Pase" name="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Pase" id="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Pase" value="<?php echo ew_HtmlEncode($estado_actual_legajo_persona->Certificado_Pase->OldValue) ?>">
<?php } ?>
<?php if ($estado_actual_legajo_persona->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $estado_actual_legajo_persona_list->RowCnt ?>_estado_actual_legajo_persona_Certificado_Pase" class="form-group estado_actual_legajo_persona_Certificado_Pase">
<div id="tp_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Pase" class="ewTemplate"><input type="radio" data-table="estado_actual_legajo_persona" data-field="x_Certificado_Pase" data-value-separator="<?php echo $estado_actual_legajo_persona->Certificado_Pase->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Pase" id="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Pase" value="{value}"<?php echo $estado_actual_legajo_persona->Certificado_Pase->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Pase" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $estado_actual_legajo_persona->Certificado_Pase->RadioButtonListHtml(FALSE, "x{$estado_actual_legajo_persona_list->RowIndex}_Certificado_Pase") ?>
</div></div>
</span>
<?php } ?>
<?php if ($estado_actual_legajo_persona->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $estado_actual_legajo_persona_list->RowCnt ?>_estado_actual_legajo_persona_Certificado_Pase" class="estado_actual_legajo_persona_Certificado_Pase">
<span<?php echo $estado_actual_legajo_persona->Certificado_Pase->ViewAttributes() ?>>
<?php echo $estado_actual_legajo_persona->Certificado_Pase->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($estado_actual_legajo_persona->Tiene_DNI->Visible) { // Tiene_DNI ?>
		<td data-name="Tiene_DNI"<?php echo $estado_actual_legajo_persona->Tiene_DNI->CellAttributes() ?>>
<?php if ($estado_actual_legajo_persona->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $estado_actual_legajo_persona_list->RowCnt ?>_estado_actual_legajo_persona_Tiene_DNI" class="form-group estado_actual_legajo_persona_Tiene_DNI">
<div id="tp_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Tiene_DNI" class="ewTemplate"><input type="radio" data-table="estado_actual_legajo_persona" data-field="x_Tiene_DNI" data-value-separator="<?php echo $estado_actual_legajo_persona->Tiene_DNI->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Tiene_DNI" id="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Tiene_DNI" value="{value}"<?php echo $estado_actual_legajo_persona->Tiene_DNI->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Tiene_DNI" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $estado_actual_legajo_persona->Tiene_DNI->RadioButtonListHtml(FALSE, "x{$estado_actual_legajo_persona_list->RowIndex}_Tiene_DNI") ?>
</div></div>
</span>
<input type="hidden" data-table="estado_actual_legajo_persona" data-field="x_Tiene_DNI" name="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Tiene_DNI" id="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Tiene_DNI" value="<?php echo ew_HtmlEncode($estado_actual_legajo_persona->Tiene_DNI->OldValue) ?>">
<?php } ?>
<?php if ($estado_actual_legajo_persona->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $estado_actual_legajo_persona_list->RowCnt ?>_estado_actual_legajo_persona_Tiene_DNI" class="form-group estado_actual_legajo_persona_Tiene_DNI">
<div id="tp_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Tiene_DNI" class="ewTemplate"><input type="radio" data-table="estado_actual_legajo_persona" data-field="x_Tiene_DNI" data-value-separator="<?php echo $estado_actual_legajo_persona->Tiene_DNI->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Tiene_DNI" id="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Tiene_DNI" value="{value}"<?php echo $estado_actual_legajo_persona->Tiene_DNI->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Tiene_DNI" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $estado_actual_legajo_persona->Tiene_DNI->RadioButtonListHtml(FALSE, "x{$estado_actual_legajo_persona_list->RowIndex}_Tiene_DNI") ?>
</div></div>
</span>
<?php } ?>
<?php if ($estado_actual_legajo_persona->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $estado_actual_legajo_persona_list->RowCnt ?>_estado_actual_legajo_persona_Tiene_DNI" class="estado_actual_legajo_persona_Tiene_DNI">
<span<?php echo $estado_actual_legajo_persona->Tiene_DNI->ViewAttributes() ?>>
<?php echo $estado_actual_legajo_persona->Tiene_DNI->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($estado_actual_legajo_persona->Certificado_Medico->Visible) { // Certificado_Medico ?>
		<td data-name="Certificado_Medico"<?php echo $estado_actual_legajo_persona->Certificado_Medico->CellAttributes() ?>>
<?php if ($estado_actual_legajo_persona->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $estado_actual_legajo_persona_list->RowCnt ?>_estado_actual_legajo_persona_Certificado_Medico" class="form-group estado_actual_legajo_persona_Certificado_Medico">
<div id="tp_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Medico" class="ewTemplate"><input type="radio" data-table="estado_actual_legajo_persona" data-field="x_Certificado_Medico" data-value-separator="<?php echo $estado_actual_legajo_persona->Certificado_Medico->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Medico" id="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Medico" value="{value}"<?php echo $estado_actual_legajo_persona->Certificado_Medico->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Medico" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $estado_actual_legajo_persona->Certificado_Medico->RadioButtonListHtml(FALSE, "x{$estado_actual_legajo_persona_list->RowIndex}_Certificado_Medico") ?>
</div></div>
</span>
<input type="hidden" data-table="estado_actual_legajo_persona" data-field="x_Certificado_Medico" name="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Medico" id="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Medico" value="<?php echo ew_HtmlEncode($estado_actual_legajo_persona->Certificado_Medico->OldValue) ?>">
<?php } ?>
<?php if ($estado_actual_legajo_persona->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $estado_actual_legajo_persona_list->RowCnt ?>_estado_actual_legajo_persona_Certificado_Medico" class="form-group estado_actual_legajo_persona_Certificado_Medico">
<div id="tp_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Medico" class="ewTemplate"><input type="radio" data-table="estado_actual_legajo_persona" data-field="x_Certificado_Medico" data-value-separator="<?php echo $estado_actual_legajo_persona->Certificado_Medico->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Medico" id="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Medico" value="{value}"<?php echo $estado_actual_legajo_persona->Certificado_Medico->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Medico" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $estado_actual_legajo_persona->Certificado_Medico->RadioButtonListHtml(FALSE, "x{$estado_actual_legajo_persona_list->RowIndex}_Certificado_Medico") ?>
</div></div>
</span>
<?php } ?>
<?php if ($estado_actual_legajo_persona->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $estado_actual_legajo_persona_list->RowCnt ?>_estado_actual_legajo_persona_Certificado_Medico" class="estado_actual_legajo_persona_Certificado_Medico">
<span<?php echo $estado_actual_legajo_persona->Certificado_Medico->ViewAttributes() ?>>
<?php echo $estado_actual_legajo_persona->Certificado_Medico->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($estado_actual_legajo_persona->Posee_Autorizacion->Visible) { // Posee_Autorizacion ?>
		<td data-name="Posee_Autorizacion"<?php echo $estado_actual_legajo_persona->Posee_Autorizacion->CellAttributes() ?>>
<?php if ($estado_actual_legajo_persona->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $estado_actual_legajo_persona_list->RowCnt ?>_estado_actual_legajo_persona_Posee_Autorizacion" class="form-group estado_actual_legajo_persona_Posee_Autorizacion">
<div id="tp_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Posee_Autorizacion" class="ewTemplate"><input type="radio" data-table="estado_actual_legajo_persona" data-field="x_Posee_Autorizacion" data-value-separator="<?php echo $estado_actual_legajo_persona->Posee_Autorizacion->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Posee_Autorizacion" id="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Posee_Autorizacion" value="{value}"<?php echo $estado_actual_legajo_persona->Posee_Autorizacion->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Posee_Autorizacion" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $estado_actual_legajo_persona->Posee_Autorizacion->RadioButtonListHtml(FALSE, "x{$estado_actual_legajo_persona_list->RowIndex}_Posee_Autorizacion") ?>
</div></div>
</span>
<input type="hidden" data-table="estado_actual_legajo_persona" data-field="x_Posee_Autorizacion" name="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Posee_Autorizacion" id="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Posee_Autorizacion" value="<?php echo ew_HtmlEncode($estado_actual_legajo_persona->Posee_Autorizacion->OldValue) ?>">
<?php } ?>
<?php if ($estado_actual_legajo_persona->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $estado_actual_legajo_persona_list->RowCnt ?>_estado_actual_legajo_persona_Posee_Autorizacion" class="form-group estado_actual_legajo_persona_Posee_Autorizacion">
<div id="tp_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Posee_Autorizacion" class="ewTemplate"><input type="radio" data-table="estado_actual_legajo_persona" data-field="x_Posee_Autorizacion" data-value-separator="<?php echo $estado_actual_legajo_persona->Posee_Autorizacion->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Posee_Autorizacion" id="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Posee_Autorizacion" value="{value}"<?php echo $estado_actual_legajo_persona->Posee_Autorizacion->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Posee_Autorizacion" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $estado_actual_legajo_persona->Posee_Autorizacion->RadioButtonListHtml(FALSE, "x{$estado_actual_legajo_persona_list->RowIndex}_Posee_Autorizacion") ?>
</div></div>
</span>
<?php } ?>
<?php if ($estado_actual_legajo_persona->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $estado_actual_legajo_persona_list->RowCnt ?>_estado_actual_legajo_persona_Posee_Autorizacion" class="estado_actual_legajo_persona_Posee_Autorizacion">
<span<?php echo $estado_actual_legajo_persona->Posee_Autorizacion->ViewAttributes() ?>>
<?php echo $estado_actual_legajo_persona->Posee_Autorizacion->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($estado_actual_legajo_persona->Cooperadora->Visible) { // Cooperadora ?>
		<td data-name="Cooperadora"<?php echo $estado_actual_legajo_persona->Cooperadora->CellAttributes() ?>>
<?php if ($estado_actual_legajo_persona->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $estado_actual_legajo_persona_list->RowCnt ?>_estado_actual_legajo_persona_Cooperadora" class="form-group estado_actual_legajo_persona_Cooperadora">
<div id="tp_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Cooperadora" class="ewTemplate"><input type="radio" data-table="estado_actual_legajo_persona" data-field="x_Cooperadora" data-value-separator="<?php echo $estado_actual_legajo_persona->Cooperadora->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Cooperadora" id="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Cooperadora" value="{value}"<?php echo $estado_actual_legajo_persona->Cooperadora->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Cooperadora" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $estado_actual_legajo_persona->Cooperadora->RadioButtonListHtml(FALSE, "x{$estado_actual_legajo_persona_list->RowIndex}_Cooperadora") ?>
</div></div>
</span>
<input type="hidden" data-table="estado_actual_legajo_persona" data-field="x_Cooperadora" name="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Cooperadora" id="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Cooperadora" value="<?php echo ew_HtmlEncode($estado_actual_legajo_persona->Cooperadora->OldValue) ?>">
<?php } ?>
<?php if ($estado_actual_legajo_persona->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $estado_actual_legajo_persona_list->RowCnt ?>_estado_actual_legajo_persona_Cooperadora" class="form-group estado_actual_legajo_persona_Cooperadora">
<div id="tp_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Cooperadora" class="ewTemplate"><input type="radio" data-table="estado_actual_legajo_persona" data-field="x_Cooperadora" data-value-separator="<?php echo $estado_actual_legajo_persona->Cooperadora->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Cooperadora" id="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Cooperadora" value="{value}"<?php echo $estado_actual_legajo_persona->Cooperadora->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Cooperadora" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $estado_actual_legajo_persona->Cooperadora->RadioButtonListHtml(FALSE, "x{$estado_actual_legajo_persona_list->RowIndex}_Cooperadora") ?>
</div></div>
</span>
<?php } ?>
<?php if ($estado_actual_legajo_persona->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $estado_actual_legajo_persona_list->RowCnt ?>_estado_actual_legajo_persona_Cooperadora" class="estado_actual_legajo_persona_Cooperadora">
<span<?php echo $estado_actual_legajo_persona->Cooperadora->ViewAttributes() ?>>
<?php echo $estado_actual_legajo_persona->Cooperadora->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($estado_actual_legajo_persona->Fecha_Actualizacion->Visible) { // Fecha_Actualizacion ?>
		<td data-name="Fecha_Actualizacion"<?php echo $estado_actual_legajo_persona->Fecha_Actualizacion->CellAttributes() ?>>
<?php if ($estado_actual_legajo_persona->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="estado_actual_legajo_persona" data-field="x_Fecha_Actualizacion" name="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Fecha_Actualizacion" id="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Fecha_Actualizacion" value="<?php echo ew_HtmlEncode($estado_actual_legajo_persona->Fecha_Actualizacion->OldValue) ?>">
<?php } ?>
<?php if ($estado_actual_legajo_persona->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($estado_actual_legajo_persona->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $estado_actual_legajo_persona_list->RowCnt ?>_estado_actual_legajo_persona_Fecha_Actualizacion" class="estado_actual_legajo_persona_Fecha_Actualizacion">
<span<?php echo $estado_actual_legajo_persona->Fecha_Actualizacion->ViewAttributes() ?>>
<?php echo $estado_actual_legajo_persona->Fecha_Actualizacion->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($estado_actual_legajo_persona->Usuario->Visible) { // Usuario ?>
		<td data-name="Usuario"<?php echo $estado_actual_legajo_persona->Usuario->CellAttributes() ?>>
<?php if ($estado_actual_legajo_persona->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="estado_actual_legajo_persona" data-field="x_Usuario" name="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Usuario" id="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Usuario" value="<?php echo ew_HtmlEncode($estado_actual_legajo_persona->Usuario->OldValue) ?>">
<?php } ?>
<?php if ($estado_actual_legajo_persona->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($estado_actual_legajo_persona->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $estado_actual_legajo_persona_list->RowCnt ?>_estado_actual_legajo_persona_Usuario" class="estado_actual_legajo_persona_Usuario">
<span<?php echo $estado_actual_legajo_persona->Usuario->ViewAttributes() ?>>
<?php echo $estado_actual_legajo_persona->Usuario->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$estado_actual_legajo_persona_list->ListOptions->Render("body", "right", $estado_actual_legajo_persona_list->RowCnt);
?>
	</tr>
<?php if ($estado_actual_legajo_persona->RowType == EW_ROWTYPE_ADD || $estado_actual_legajo_persona->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
festado_actual_legajo_personalist.UpdateOpts(<?php echo $estado_actual_legajo_persona_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($estado_actual_legajo_persona->CurrentAction <> "gridadd")
		if (!$estado_actual_legajo_persona_list->Recordset->EOF) $estado_actual_legajo_persona_list->Recordset->MoveNext();
}
?>
<?php
	if ($estado_actual_legajo_persona->CurrentAction == "gridadd" || $estado_actual_legajo_persona->CurrentAction == "gridedit") {
		$estado_actual_legajo_persona_list->RowIndex = '$rowindex$';
		$estado_actual_legajo_persona_list->LoadDefaultValues();

		// Set row properties
		$estado_actual_legajo_persona->ResetAttrs();
		$estado_actual_legajo_persona->RowAttrs = array_merge($estado_actual_legajo_persona->RowAttrs, array('data-rowindex'=>$estado_actual_legajo_persona_list->RowIndex, 'id'=>'r0_estado_actual_legajo_persona', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($estado_actual_legajo_persona->RowAttrs["class"], "ewTemplate");
		$estado_actual_legajo_persona->RowType = EW_ROWTYPE_ADD;

		// Render row
		$estado_actual_legajo_persona_list->RenderRow();

		// Render list options
		$estado_actual_legajo_persona_list->RenderListOptions();
		$estado_actual_legajo_persona_list->StartRowCnt = 0;
?>
	<tr<?php echo $estado_actual_legajo_persona->RowAttributes() ?>>
<?php

// Render list options (body, left)
$estado_actual_legajo_persona_list->ListOptions->Render("body", "left", $estado_actual_legajo_persona_list->RowIndex);
?>
	<?php if ($estado_actual_legajo_persona->Matricula->Visible) { // Matricula ?>
		<td data-name="Matricula">
<span id="el$rowindex$_estado_actual_legajo_persona_Matricula" class="form-group estado_actual_legajo_persona_Matricula">
<div id="tp_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Matricula" class="ewTemplate"><input type="radio" data-table="estado_actual_legajo_persona" data-field="x_Matricula" data-value-separator="<?php echo $estado_actual_legajo_persona->Matricula->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Matricula" id="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Matricula" value="{value}"<?php echo $estado_actual_legajo_persona->Matricula->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Matricula" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $estado_actual_legajo_persona->Matricula->RadioButtonListHtml(FALSE, "x{$estado_actual_legajo_persona_list->RowIndex}_Matricula") ?>
</div></div>
</span>
<input type="hidden" data-table="estado_actual_legajo_persona" data-field="x_Matricula" name="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Matricula" id="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Matricula" value="<?php echo ew_HtmlEncode($estado_actual_legajo_persona->Matricula->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($estado_actual_legajo_persona->Certificado_Pase->Visible) { // Certificado_Pase ?>
		<td data-name="Certificado_Pase">
<span id="el$rowindex$_estado_actual_legajo_persona_Certificado_Pase" class="form-group estado_actual_legajo_persona_Certificado_Pase">
<div id="tp_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Pase" class="ewTemplate"><input type="radio" data-table="estado_actual_legajo_persona" data-field="x_Certificado_Pase" data-value-separator="<?php echo $estado_actual_legajo_persona->Certificado_Pase->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Pase" id="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Pase" value="{value}"<?php echo $estado_actual_legajo_persona->Certificado_Pase->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Pase" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $estado_actual_legajo_persona->Certificado_Pase->RadioButtonListHtml(FALSE, "x{$estado_actual_legajo_persona_list->RowIndex}_Certificado_Pase") ?>
</div></div>
</span>
<input type="hidden" data-table="estado_actual_legajo_persona" data-field="x_Certificado_Pase" name="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Pase" id="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Pase" value="<?php echo ew_HtmlEncode($estado_actual_legajo_persona->Certificado_Pase->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($estado_actual_legajo_persona->Tiene_DNI->Visible) { // Tiene_DNI ?>
		<td data-name="Tiene_DNI">
<span id="el$rowindex$_estado_actual_legajo_persona_Tiene_DNI" class="form-group estado_actual_legajo_persona_Tiene_DNI">
<div id="tp_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Tiene_DNI" class="ewTemplate"><input type="radio" data-table="estado_actual_legajo_persona" data-field="x_Tiene_DNI" data-value-separator="<?php echo $estado_actual_legajo_persona->Tiene_DNI->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Tiene_DNI" id="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Tiene_DNI" value="{value}"<?php echo $estado_actual_legajo_persona->Tiene_DNI->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Tiene_DNI" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $estado_actual_legajo_persona->Tiene_DNI->RadioButtonListHtml(FALSE, "x{$estado_actual_legajo_persona_list->RowIndex}_Tiene_DNI") ?>
</div></div>
</span>
<input type="hidden" data-table="estado_actual_legajo_persona" data-field="x_Tiene_DNI" name="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Tiene_DNI" id="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Tiene_DNI" value="<?php echo ew_HtmlEncode($estado_actual_legajo_persona->Tiene_DNI->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($estado_actual_legajo_persona->Certificado_Medico->Visible) { // Certificado_Medico ?>
		<td data-name="Certificado_Medico">
<span id="el$rowindex$_estado_actual_legajo_persona_Certificado_Medico" class="form-group estado_actual_legajo_persona_Certificado_Medico">
<div id="tp_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Medico" class="ewTemplate"><input type="radio" data-table="estado_actual_legajo_persona" data-field="x_Certificado_Medico" data-value-separator="<?php echo $estado_actual_legajo_persona->Certificado_Medico->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Medico" id="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Medico" value="{value}"<?php echo $estado_actual_legajo_persona->Certificado_Medico->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Medico" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $estado_actual_legajo_persona->Certificado_Medico->RadioButtonListHtml(FALSE, "x{$estado_actual_legajo_persona_list->RowIndex}_Certificado_Medico") ?>
</div></div>
</span>
<input type="hidden" data-table="estado_actual_legajo_persona" data-field="x_Certificado_Medico" name="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Medico" id="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Certificado_Medico" value="<?php echo ew_HtmlEncode($estado_actual_legajo_persona->Certificado_Medico->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($estado_actual_legajo_persona->Posee_Autorizacion->Visible) { // Posee_Autorizacion ?>
		<td data-name="Posee_Autorizacion">
<span id="el$rowindex$_estado_actual_legajo_persona_Posee_Autorizacion" class="form-group estado_actual_legajo_persona_Posee_Autorizacion">
<div id="tp_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Posee_Autorizacion" class="ewTemplate"><input type="radio" data-table="estado_actual_legajo_persona" data-field="x_Posee_Autorizacion" data-value-separator="<?php echo $estado_actual_legajo_persona->Posee_Autorizacion->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Posee_Autorizacion" id="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Posee_Autorizacion" value="{value}"<?php echo $estado_actual_legajo_persona->Posee_Autorizacion->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Posee_Autorizacion" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $estado_actual_legajo_persona->Posee_Autorizacion->RadioButtonListHtml(FALSE, "x{$estado_actual_legajo_persona_list->RowIndex}_Posee_Autorizacion") ?>
</div></div>
</span>
<input type="hidden" data-table="estado_actual_legajo_persona" data-field="x_Posee_Autorizacion" name="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Posee_Autorizacion" id="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Posee_Autorizacion" value="<?php echo ew_HtmlEncode($estado_actual_legajo_persona->Posee_Autorizacion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($estado_actual_legajo_persona->Cooperadora->Visible) { // Cooperadora ?>
		<td data-name="Cooperadora">
<span id="el$rowindex$_estado_actual_legajo_persona_Cooperadora" class="form-group estado_actual_legajo_persona_Cooperadora">
<div id="tp_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Cooperadora" class="ewTemplate"><input type="radio" data-table="estado_actual_legajo_persona" data-field="x_Cooperadora" data-value-separator="<?php echo $estado_actual_legajo_persona->Cooperadora->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Cooperadora" id="x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Cooperadora" value="{value}"<?php echo $estado_actual_legajo_persona->Cooperadora->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Cooperadora" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $estado_actual_legajo_persona->Cooperadora->RadioButtonListHtml(FALSE, "x{$estado_actual_legajo_persona_list->RowIndex}_Cooperadora") ?>
</div></div>
</span>
<input type="hidden" data-table="estado_actual_legajo_persona" data-field="x_Cooperadora" name="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Cooperadora" id="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Cooperadora" value="<?php echo ew_HtmlEncode($estado_actual_legajo_persona->Cooperadora->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($estado_actual_legajo_persona->Fecha_Actualizacion->Visible) { // Fecha_Actualizacion ?>
		<td data-name="Fecha_Actualizacion">
<input type="hidden" data-table="estado_actual_legajo_persona" data-field="x_Fecha_Actualizacion" name="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Fecha_Actualizacion" id="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Fecha_Actualizacion" value="<?php echo ew_HtmlEncode($estado_actual_legajo_persona->Fecha_Actualizacion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($estado_actual_legajo_persona->Usuario->Visible) { // Usuario ?>
		<td data-name="Usuario">
<input type="hidden" data-table="estado_actual_legajo_persona" data-field="x_Usuario" name="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Usuario" id="o<?php echo $estado_actual_legajo_persona_list->RowIndex ?>_Usuario" value="<?php echo ew_HtmlEncode($estado_actual_legajo_persona->Usuario->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$estado_actual_legajo_persona_list->ListOptions->Render("body", "right", $estado_actual_legajo_persona_list->RowCnt);
?>
<script type="text/javascript">
festado_actual_legajo_personalist.UpdateOpts(<?php echo $estado_actual_legajo_persona_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($estado_actual_legajo_persona->CurrentAction == "add" || $estado_actual_legajo_persona->CurrentAction == "copy") { ?>
<input type="hidden" name="<?php echo $estado_actual_legajo_persona_list->FormKeyCountName ?>" id="<?php echo $estado_actual_legajo_persona_list->FormKeyCountName ?>" value="<?php echo $estado_actual_legajo_persona_list->KeyCount ?>">
<?php } ?>
<?php if ($estado_actual_legajo_persona->CurrentAction == "gridadd") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $estado_actual_legajo_persona_list->FormKeyCountName ?>" id="<?php echo $estado_actual_legajo_persona_list->FormKeyCountName ?>" value="<?php echo $estado_actual_legajo_persona_list->KeyCount ?>">
<?php echo $estado_actual_legajo_persona_list->MultiSelectKey ?>
<?php } ?>
<?php if ($estado_actual_legajo_persona->CurrentAction == "edit") { ?>
<input type="hidden" name="<?php echo $estado_actual_legajo_persona_list->FormKeyCountName ?>" id="<?php echo $estado_actual_legajo_persona_list->FormKeyCountName ?>" value="<?php echo $estado_actual_legajo_persona_list->KeyCount ?>">
<?php } ?>
<?php if ($estado_actual_legajo_persona->CurrentAction == "gridedit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $estado_actual_legajo_persona_list->FormKeyCountName ?>" id="<?php echo $estado_actual_legajo_persona_list->FormKeyCountName ?>" value="<?php echo $estado_actual_legajo_persona_list->KeyCount ?>">
<?php echo $estado_actual_legajo_persona_list->MultiSelectKey ?>
<?php } ?>
<?php if ($estado_actual_legajo_persona->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($estado_actual_legajo_persona_list->Recordset)
	$estado_actual_legajo_persona_list->Recordset->Close();
?>
<?php if ($estado_actual_legajo_persona->Export == "") { ?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($estado_actual_legajo_persona->CurrentAction <> "gridadd" && $estado_actual_legajo_persona->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($estado_actual_legajo_persona_list->Pager)) $estado_actual_legajo_persona_list->Pager = new cPrevNextPager($estado_actual_legajo_persona_list->StartRec, $estado_actual_legajo_persona_list->DisplayRecs, $estado_actual_legajo_persona_list->TotalRecs) ?>
<?php if ($estado_actual_legajo_persona_list->Pager->RecordCount > 0 && $estado_actual_legajo_persona_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($estado_actual_legajo_persona_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $estado_actual_legajo_persona_list->PageUrl() ?>start=<?php echo $estado_actual_legajo_persona_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($estado_actual_legajo_persona_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $estado_actual_legajo_persona_list->PageUrl() ?>start=<?php echo $estado_actual_legajo_persona_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $estado_actual_legajo_persona_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($estado_actual_legajo_persona_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $estado_actual_legajo_persona_list->PageUrl() ?>start=<?php echo $estado_actual_legajo_persona_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($estado_actual_legajo_persona_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $estado_actual_legajo_persona_list->PageUrl() ?>start=<?php echo $estado_actual_legajo_persona_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $estado_actual_legajo_persona_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $estado_actual_legajo_persona_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $estado_actual_legajo_persona_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $estado_actual_legajo_persona_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($estado_actual_legajo_persona_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($estado_actual_legajo_persona_list->TotalRecs == 0 && $estado_actual_legajo_persona->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($estado_actual_legajo_persona_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($estado_actual_legajo_persona->Export == "") { ?>
<script type="text/javascript">
festado_actual_legajo_personalistsrch.FilterList = <?php echo $estado_actual_legajo_persona_list->GetFilterList() ?>;
festado_actual_legajo_personalistsrch.Init();
festado_actual_legajo_personalist.Init();
</script>
<?php } ?>
<?php
$estado_actual_legajo_persona_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($estado_actual_legajo_persona->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$estado_actual_legajo_persona_list->Page_Terminate();
?>
