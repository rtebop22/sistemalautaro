<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$usuarios_list = NULL; // Initialize page object first

class cusuarios_list extends cusuarios {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{9FD9BA28-0339-4B41-9A45-0CAE935EFE3A}";

	// Table name
	var $TableName = 'usuarios';

	// Page object name
	var $PageObjName = 'usuarios_list';

	// Grid form hidden field names
	var $FormName = 'fusuarioslist';
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

		// Table object (usuarios)
		if (!isset($GLOBALS["usuarios"]) || get_class($GLOBALS["usuarios"]) == "cusuarios") {
			$GLOBALS["usuarios"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["usuarios"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "usuariosadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "usuariosdelete.php";
		$this->MultiUpdateUrl = "usuariosupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'usuarios', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fusuarioslistsrch";

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
			if (strval($Security->CurrentUserID()) == "") {
				$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
				$this->Page_Terminate();
			}
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
		$this->NombreTitular->SetVisibility();
		$this->Dni->SetVisibility();
		$this->Nombre->SetVisibility();
		$this->Password->SetVisibility();
		$this->Nivel_Usuario->SetVisibility();
		$this->Curso->SetVisibility();
		$this->Turno->SetVisibility();
		$this->Division->SetVisibility();

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
		global $EW_EXPORT, $usuarios;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($usuarios);
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
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

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
		$this->setKey("Nombre", ""); // Clear inline edit key
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
		if (@$_GET["Nombre"] <> "") {
			$this->Nombre->setQueryStringValue($_GET["Nombre"]);
		} else {
			$bInlineEdit = FALSE;
		}
		if ($bInlineEdit) {
			if ($this->LoadRow()) {

				// Check if valid user id
				if (!$this->ShowOptionLink('edit')) {
					$sUserIdMsg = $Language->Phrase("NoEditPermission");
					$this->setFailureMessage($sUserIdMsg);
					$this->ClearInlineMode(); // Clear inline edit mode
					return;
				}
				$this->setKey("Nombre", $this->Nombre->CurrentValue); // Set up inline edit key
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
		if (strval($this->getKey("Nombre")) <> strval($this->Nombre->CurrentValue))
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
			$this->Nombre->setFormValue($arrKeyFlds[0]);
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
					$sKey .= $this->Nombre->CurrentValue;

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
		if ($objForm->HasValue("x_NombreTitular") && $objForm->HasValue("o_NombreTitular") && $this->NombreTitular->CurrentValue <> $this->NombreTitular->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_Dni") && $objForm->HasValue("o_Dni") && $this->Dni->CurrentValue <> $this->Dni->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_Nombre") && $objForm->HasValue("o_Nombre") && $this->Nombre->CurrentValue <> $this->Nombre->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_Password") && $objForm->HasValue("o_Password") && $this->Password->CurrentValue <> $this->Password->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_Nivel_Usuario") && $objForm->HasValue("o_Nivel_Usuario") && $this->Nivel_Usuario->CurrentValue <> $this->Nivel_Usuario->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_Curso") && $objForm->HasValue("o_Curso") && $this->Curso->CurrentValue <> $this->Curso->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_Turno") && $objForm->HasValue("o_Turno") && $this->Turno->CurrentValue <> $this->Turno->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_Division") && $objForm->HasValue("o_Division") && $this->Division->CurrentValue <> $this->Division->OldValue)
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
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fusuarioslistsrch");
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->NombreTitular->AdvancedSearch->ToJSON(), ","); // Field NombreTitular
		$sFilterList = ew_Concat($sFilterList, $this->Dni->AdvancedSearch->ToJSON(), ","); // Field Dni
		$sFilterList = ew_Concat($sFilterList, $this->Nombre->AdvancedSearch->ToJSON(), ","); // Field Nombre
		$sFilterList = ew_Concat($sFilterList, $this->Password->AdvancedSearch->ToJSON(), ","); // Field Password
		$sFilterList = ew_Concat($sFilterList, $this->Nivel_Usuario->AdvancedSearch->ToJSON(), ","); // Field Nivel_Usuario
		$sFilterList = ew_Concat($sFilterList, $this->Curso->AdvancedSearch->ToJSON(), ","); // Field Curso
		$sFilterList = ew_Concat($sFilterList, $this->Turno->AdvancedSearch->ToJSON(), ","); // Field Turno
		$sFilterList = ew_Concat($sFilterList, $this->Division->AdvancedSearch->ToJSON(), ","); // Field Division
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fusuarioslistsrch", $filters);
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

		// Field NombreTitular
		$this->NombreTitular->AdvancedSearch->SearchValue = @$filter["x_NombreTitular"];
		$this->NombreTitular->AdvancedSearch->SearchOperator = @$filter["z_NombreTitular"];
		$this->NombreTitular->AdvancedSearch->SearchCondition = @$filter["v_NombreTitular"];
		$this->NombreTitular->AdvancedSearch->SearchValue2 = @$filter["y_NombreTitular"];
		$this->NombreTitular->AdvancedSearch->SearchOperator2 = @$filter["w_NombreTitular"];
		$this->NombreTitular->AdvancedSearch->Save();

		// Field Dni
		$this->Dni->AdvancedSearch->SearchValue = @$filter["x_Dni"];
		$this->Dni->AdvancedSearch->SearchOperator = @$filter["z_Dni"];
		$this->Dni->AdvancedSearch->SearchCondition = @$filter["v_Dni"];
		$this->Dni->AdvancedSearch->SearchValue2 = @$filter["y_Dni"];
		$this->Dni->AdvancedSearch->SearchOperator2 = @$filter["w_Dni"];
		$this->Dni->AdvancedSearch->Save();

		// Field Nombre
		$this->Nombre->AdvancedSearch->SearchValue = @$filter["x_Nombre"];
		$this->Nombre->AdvancedSearch->SearchOperator = @$filter["z_Nombre"];
		$this->Nombre->AdvancedSearch->SearchCondition = @$filter["v_Nombre"];
		$this->Nombre->AdvancedSearch->SearchValue2 = @$filter["y_Nombre"];
		$this->Nombre->AdvancedSearch->SearchOperator2 = @$filter["w_Nombre"];
		$this->Nombre->AdvancedSearch->Save();

		// Field Password
		$this->Password->AdvancedSearch->SearchValue = @$filter["x_Password"];
		$this->Password->AdvancedSearch->SearchOperator = @$filter["z_Password"];
		$this->Password->AdvancedSearch->SearchCondition = @$filter["v_Password"];
		$this->Password->AdvancedSearch->SearchValue2 = @$filter["y_Password"];
		$this->Password->AdvancedSearch->SearchOperator2 = @$filter["w_Password"];
		$this->Password->AdvancedSearch->Save();

		// Field Nivel_Usuario
		$this->Nivel_Usuario->AdvancedSearch->SearchValue = @$filter["x_Nivel_Usuario"];
		$this->Nivel_Usuario->AdvancedSearch->SearchOperator = @$filter["z_Nivel_Usuario"];
		$this->Nivel_Usuario->AdvancedSearch->SearchCondition = @$filter["v_Nivel_Usuario"];
		$this->Nivel_Usuario->AdvancedSearch->SearchValue2 = @$filter["y_Nivel_Usuario"];
		$this->Nivel_Usuario->AdvancedSearch->SearchOperator2 = @$filter["w_Nivel_Usuario"];
		$this->Nivel_Usuario->AdvancedSearch->Save();

		// Field Curso
		$this->Curso->AdvancedSearch->SearchValue = @$filter["x_Curso"];
		$this->Curso->AdvancedSearch->SearchOperator = @$filter["z_Curso"];
		$this->Curso->AdvancedSearch->SearchCondition = @$filter["v_Curso"];
		$this->Curso->AdvancedSearch->SearchValue2 = @$filter["y_Curso"];
		$this->Curso->AdvancedSearch->SearchOperator2 = @$filter["w_Curso"];
		$this->Curso->AdvancedSearch->Save();

		// Field Turno
		$this->Turno->AdvancedSearch->SearchValue = @$filter["x_Turno"];
		$this->Turno->AdvancedSearch->SearchOperator = @$filter["z_Turno"];
		$this->Turno->AdvancedSearch->SearchCondition = @$filter["v_Turno"];
		$this->Turno->AdvancedSearch->SearchValue2 = @$filter["y_Turno"];
		$this->Turno->AdvancedSearch->SearchOperator2 = @$filter["w_Turno"];
		$this->Turno->AdvancedSearch->Save();

		// Field Division
		$this->Division->AdvancedSearch->SearchValue = @$filter["x_Division"];
		$this->Division->AdvancedSearch->SearchOperator = @$filter["z_Division"];
		$this->Division->AdvancedSearch->SearchCondition = @$filter["v_Division"];
		$this->Division->AdvancedSearch->SearchValue2 = @$filter["y_Division"];
		$this->Division->AdvancedSearch->SearchOperator2 = @$filter["w_Division"];
		$this->Division->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->NombreTitular, $Default, FALSE); // NombreTitular
		$this->BuildSearchSql($sWhere, $this->Dni, $Default, FALSE); // Dni
		$this->BuildSearchSql($sWhere, $this->Nombre, $Default, FALSE); // Nombre
		$this->BuildSearchSql($sWhere, $this->Password, $Default, FALSE); // Password
		$this->BuildSearchSql($sWhere, $this->Nivel_Usuario, $Default, FALSE); // Nivel_Usuario
		$this->BuildSearchSql($sWhere, $this->Curso, $Default, FALSE); // Curso
		$this->BuildSearchSql($sWhere, $this->Turno, $Default, FALSE); // Turno
		$this->BuildSearchSql($sWhere, $this->Division, $Default, FALSE); // Division

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->NombreTitular->AdvancedSearch->Save(); // NombreTitular
			$this->Dni->AdvancedSearch->Save(); // Dni
			$this->Nombre->AdvancedSearch->Save(); // Nombre
			$this->Password->AdvancedSearch->Save(); // Password
			$this->Nivel_Usuario->AdvancedSearch->Save(); // Nivel_Usuario
			$this->Curso->AdvancedSearch->Save(); // Curso
			$this->Turno->AdvancedSearch->Save(); // Turno
			$this->Division->AdvancedSearch->Save(); // Division
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
		$this->BuildBasicSearchSQL($sWhere, $this->NombreTitular, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Nombre, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Password, $arKeywords, $type);
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
		if ($this->NombreTitular->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Dni->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Nombre->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Password->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Nivel_Usuario->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Curso->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Turno->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Division->AdvancedSearch->IssetSession())
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
		$this->NombreTitular->AdvancedSearch->UnsetSession();
		$this->Dni->AdvancedSearch->UnsetSession();
		$this->Nombre->AdvancedSearch->UnsetSession();
		$this->Password->AdvancedSearch->UnsetSession();
		$this->Nivel_Usuario->AdvancedSearch->UnsetSession();
		$this->Curso->AdvancedSearch->UnsetSession();
		$this->Turno->AdvancedSearch->UnsetSession();
		$this->Division->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->NombreTitular->AdvancedSearch->Load();
		$this->Dni->AdvancedSearch->Load();
		$this->Nombre->AdvancedSearch->Load();
		$this->Password->AdvancedSearch->Load();
		$this->Nivel_Usuario->AdvancedSearch->Load();
		$this->Curso->AdvancedSearch->Load();
		$this->Turno->AdvancedSearch->Load();
		$this->Division->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->NombreTitular); // NombreTitular
			$this->UpdateSort($this->Dni); // Dni
			$this->UpdateSort($this->Nombre); // Nombre
			$this->UpdateSort($this->Password); // Password
			$this->UpdateSort($this->Nivel_Usuario); // Nivel_Usuario
			$this->UpdateSort($this->Curso); // Curso
			$this->UpdateSort($this->Turno); // Turno
			$this->UpdateSort($this->Division); // Division
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

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->NombreTitular->setSort("");
				$this->Dni->setSort("");
				$this->Nombre->setSort("");
				$this->Password->setSort("");
				$this->Nivel_Usuario->setSort("");
				$this->Curso->setSort("");
				$this->Turno->setSort("");
				$this->Division->setSort("");
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
			$oListOpt->Body .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_key\" id=\"k" . $this->RowIndex . "_key\" value=\"" . ew_HtmlEncode($this->Nombre->CurrentValue) . "\">";
			return;
		}

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		$viewcaption = ew_HtmlTitle($Language->Phrase("ViewLink"));
		if ($Security->CanView() && $this->ShowOptionLink('view')) {
			if (ew_IsMobile())
				$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
			else
				$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-table=\"usuarios\" data-caption=\"" . $viewcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->ViewUrl) . "'});\">" . $Language->Phrase("ViewLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if ($Security->CanEdit() && $this->ShowOptionLink('edit')) {
			if (ew_IsMobile())
				$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
			else
				$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . $editcaption . "\" data-table=\"usuarios\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->EditUrl) . "',caption:'" . $editcaption . "'});\">" . $Language->Phrase("EditLink") . "</a>";
			$oListOpt->Body .= "<a class=\"ewRowLink ewInlineEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" href=\"" . ew_HtmlEncode(ew_GetHashUrl($this->InlineEditUrl, $this->PageObjName . "_row_" . $this->RowCnt)) . "\">" . $Language->Phrase("InlineEditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		$copycaption = ew_HtmlTitle($Language->Phrase("CopyLink"));
		if ($Security->CanAdd() && $this->ShowOptionLink('add')) {
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->Nombre->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
		if ($this->CurrentAction == "gridedit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->Nombre->CurrentValue . "\">";
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
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitAction(event,{f:document.fusuarioslist,url:'" . $this->MultiDeleteUrl . "',msg:ewLanguage.Phrase('DeleteConfirmMsg')});return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
		$item->Visible = ($Security->CanDelete());

		// Add multi update
		$item = &$option->Add("multiupdate");
		$item->Body = "<a class=\"ewAction ewMultiUpdate\" title=\"" . ew_HtmlTitle($Language->Phrase("UpdateSelectedLink")) . "\" data-table=\"usuarios\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("UpdateSelectedLink")) . "\" href=\"\" onclick=\"ew_ModalDialogShow({lnk:this,f:document.fusuarioslist,url:'" . $this->MultiUpdateUrl . "',caption:'" . $Language->Phrase("UpdateBtn") . "'});return false;\">" . $Language->Phrase("UpdateSelectedLink") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fusuarioslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fusuarioslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fusuarioslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
					$user = $row['Nombre'];
					if ($userlist <> "") $userlist .= ",";
					$userlist .= $user;
					if ($UserAction == "resendregisteremail")
						$Processed = FALSE;
					elseif ($UserAction == "resetconcurrentuser")
						$Processed = FALSE;
					elseif ($UserAction == "resetloginretry")
						$Processed = FALSE;
					elseif ($UserAction == "setpasswordexpired")
						$Processed = FALSE;
					else
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fusuarioslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Advanced search button
		$item = &$this->SearchOptions->Add("advancedsearch");
		$item->Body = "<a class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" href=\"usuariossrch.php\">" . $Language->Phrase("AdvancedSearchBtn") . "</a>";
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
		$this->NombreTitular->CurrentValue = NULL;
		$this->NombreTitular->OldValue = $this->NombreTitular->CurrentValue;
		$this->Dni->CurrentValue = NULL;
		$this->Dni->OldValue = $this->Dni->CurrentValue;
		$this->Nombre->CurrentValue = NULL;
		$this->Nombre->OldValue = $this->Nombre->CurrentValue;
		$this->Password->CurrentValue = NULL;
		$this->Password->OldValue = $this->Password->CurrentValue;
		$this->Nivel_Usuario->CurrentValue = NULL;
		$this->Nivel_Usuario->OldValue = $this->Nivel_Usuario->CurrentValue;
		$this->Curso->CurrentValue = NULL;
		$this->Curso->OldValue = $this->Curso->CurrentValue;
		$this->Turno->CurrentValue = NULL;
		$this->Turno->OldValue = $this->Turno->CurrentValue;
		$this->Division->CurrentValue = NULL;
		$this->Division->OldValue = $this->Division->CurrentValue;
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
		// NombreTitular

		$this->NombreTitular->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_NombreTitular"]);
		if ($this->NombreTitular->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->NombreTitular->AdvancedSearch->SearchOperator = @$_GET["z_NombreTitular"];

		// Dni
		$this->Dni->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Dni"]);
		if ($this->Dni->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Dni->AdvancedSearch->SearchOperator = @$_GET["z_Dni"];

		// Nombre
		$this->Nombre->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Nombre"]);
		if ($this->Nombre->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Nombre->AdvancedSearch->SearchOperator = @$_GET["z_Nombre"];

		// Password
		$this->Password->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Password"]);
		if ($this->Password->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Password->AdvancedSearch->SearchOperator = @$_GET["z_Password"];

		// Nivel_Usuario
		$this->Nivel_Usuario->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Nivel_Usuario"]);
		if ($this->Nivel_Usuario->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Nivel_Usuario->AdvancedSearch->SearchOperator = @$_GET["z_Nivel_Usuario"];

		// Curso
		$this->Curso->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Curso"]);
		if ($this->Curso->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Curso->AdvancedSearch->SearchOperator = @$_GET["z_Curso"];

		// Turno
		$this->Turno->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Turno"]);
		if ($this->Turno->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Turno->AdvancedSearch->SearchOperator = @$_GET["z_Turno"];

		// Division
		$this->Division->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Division"]);
		if ($this->Division->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Division->AdvancedSearch->SearchOperator = @$_GET["z_Division"];
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->NombreTitular->FldIsDetailKey) {
			$this->NombreTitular->setFormValue($objForm->GetValue("x_NombreTitular"));
		}
		$this->NombreTitular->setOldValue($objForm->GetValue("o_NombreTitular"));
		if (!$this->Dni->FldIsDetailKey) {
			$this->Dni->setFormValue($objForm->GetValue("x_Dni"));
		}
		$this->Dni->setOldValue($objForm->GetValue("o_Dni"));
		if (!$this->Nombre->FldIsDetailKey) {
			$this->Nombre->setFormValue($objForm->GetValue("x_Nombre"));
		}
		$this->Nombre->setOldValue($objForm->GetValue("o_Nombre"));
		if (!$this->Password->FldIsDetailKey) {
			$this->Password->setFormValue($objForm->GetValue("x_Password"));
		}
		$this->Password->setOldValue($objForm->GetValue("o_Password"));
		if (!$this->Nivel_Usuario->FldIsDetailKey) {
			$this->Nivel_Usuario->setFormValue($objForm->GetValue("x_Nivel_Usuario"));
		}
		$this->Nivel_Usuario->setOldValue($objForm->GetValue("o_Nivel_Usuario"));
		if (!$this->Curso->FldIsDetailKey) {
			$this->Curso->setFormValue($objForm->GetValue("x_Curso"));
		}
		$this->Curso->setOldValue($objForm->GetValue("o_Curso"));
		if (!$this->Turno->FldIsDetailKey) {
			$this->Turno->setFormValue($objForm->GetValue("x_Turno"));
		}
		$this->Turno->setOldValue($objForm->GetValue("o_Turno"));
		if (!$this->Division->FldIsDetailKey) {
			$this->Division->setFormValue($objForm->GetValue("x_Division"));
		}
		$this->Division->setOldValue($objForm->GetValue("o_Division"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->NombreTitular->CurrentValue = $this->NombreTitular->FormValue;
		$this->Dni->CurrentValue = $this->Dni->FormValue;
		$this->Nombre->CurrentValue = $this->Nombre->FormValue;
		$this->Password->CurrentValue = $this->Password->FormValue;
		$this->Nivel_Usuario->CurrentValue = $this->Nivel_Usuario->FormValue;
		$this->Curso->CurrentValue = $this->Curso->FormValue;
		$this->Turno->CurrentValue = $this->Turno->FormValue;
		$this->Division->CurrentValue = $this->Division->FormValue;
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
		$this->NombreTitular->setDbValue($rs->fields('NombreTitular'));
		$this->Dni->setDbValue($rs->fields('Dni'));
		$this->Nombre->setDbValue($rs->fields('Nombre'));
		$this->Password->setDbValue($rs->fields('Password'));
		$this->Nivel_Usuario->setDbValue($rs->fields('Nivel_Usuario'));
		$this->Curso->setDbValue($rs->fields('Curso'));
		$this->Turno->setDbValue($rs->fields('Turno'));
		$this->Division->setDbValue($rs->fields('Division'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->NombreTitular->DbValue = $row['NombreTitular'];
		$this->Dni->DbValue = $row['Dni'];
		$this->Nombre->DbValue = $row['Nombre'];
		$this->Password->DbValue = $row['Password'];
		$this->Nivel_Usuario->DbValue = $row['Nivel_Usuario'];
		$this->Curso->DbValue = $row['Curso'];
		$this->Turno->DbValue = $row['Turno'];
		$this->Division->DbValue = $row['Division'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("Nombre")) <> "")
			$this->Nombre->CurrentValue = $this->getKey("Nombre"); // Nombre
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
		// NombreTitular
		// Dni
		// Nombre
		// Password
		// Nivel_Usuario
		// Curso
		// Turno
		// Division

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// NombreTitular
		$this->NombreTitular->ViewValue = $this->NombreTitular->CurrentValue;
		if (strval($this->NombreTitular->CurrentValue) <> "") {
			$sFilterWrk = "`Apellidos_Nombres`" . ew_SearchString("=", $this->NombreTitular->CurrentValue, EW_DATATYPE_MEMO, "");
		$sSqlWrk = "SELECT `Apellidos_Nombres`, `Apellidos_Nombres` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `personas`";
		$sWhereWrk = "";
		$this->NombreTitular->LookupFilters = array("dx1" => "`Apellidos_Nombres`");
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->NombreTitular, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->NombreTitular->ViewValue = $this->NombreTitular->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->NombreTitular->ViewValue = $this->NombreTitular->CurrentValue;
			}
		} else {
			$this->NombreTitular->ViewValue = NULL;
		}
		$this->NombreTitular->ViewCustomAttributes = "";

		// Dni
		$this->Dni->ViewValue = $this->Dni->CurrentValue;
		$this->Dni->ViewCustomAttributes = "";

		// Nombre
		$this->Nombre->ViewValue = $this->Nombre->CurrentValue;
		$this->Nombre->ViewCustomAttributes = "";

		// Password
		$this->Password->ViewValue = $this->Password->CurrentValue;
		$this->Password->ViewCustomAttributes = "";

		// Nivel_Usuario
		if ($Security->CanAdmin()) { // System admin
		if (strval($this->Nivel_Usuario->CurrentValue) <> "") {
			$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->Nivel_Usuario->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `userlevels`";
		$sWhereWrk = "";
		$this->Nivel_Usuario->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Nivel_Usuario, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Nivel_Usuario->ViewValue = $this->Nivel_Usuario->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Nivel_Usuario->ViewValue = $this->Nivel_Usuario->CurrentValue;
			}
		} else {
			$this->Nivel_Usuario->ViewValue = NULL;
		}
		} else {
			$this->Nivel_Usuario->ViewValue = $Language->Phrase("PasswordMask");
		}
		$this->Nivel_Usuario->ViewCustomAttributes = "";

		// Curso
		if (strval($this->Curso->CurrentValue) <> "") {
			$sFilterWrk = "`Id_Curso`" . ew_SearchString("=", $this->Curso->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `Id_Curso`, `Descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cursos`";
		$sWhereWrk = "";
		$this->Curso->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Curso, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Curso->ViewValue = $this->Curso->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Curso->ViewValue = $this->Curso->CurrentValue;
			}
		} else {
			$this->Curso->ViewValue = NULL;
		}
		$this->Curso->ViewCustomAttributes = "";

		// Turno
		if (strval($this->Turno->CurrentValue) <> "") {
			$sFilterWrk = "`Id_Turno`" . ew_SearchString("=", $this->Turno->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `Id_Turno`, `Descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `turno`";
		$sWhereWrk = "";
		$this->Turno->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Turno, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Turno->ViewValue = $this->Turno->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Turno->ViewValue = $this->Turno->CurrentValue;
			}
		} else {
			$this->Turno->ViewValue = NULL;
		}
		$this->Turno->ViewCustomAttributes = "";

		// Division
		if (strval($this->Division->CurrentValue) <> "") {
			$sFilterWrk = "`Id_Division`" . ew_SearchString("=", $this->Division->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `Id_Division`, `Descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `division`";
		$sWhereWrk = "";
		$this->Division->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Division, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Division->ViewValue = $this->Division->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Division->ViewValue = $this->Division->CurrentValue;
			}
		} else {
			$this->Division->ViewValue = NULL;
		}
		$this->Division->ViewCustomAttributes = "";

			// NombreTitular
			$this->NombreTitular->LinkCustomAttributes = "";
			$this->NombreTitular->HrefValue = "";
			$this->NombreTitular->TooltipValue = "";

			// Dni
			$this->Dni->LinkCustomAttributes = "";
			$this->Dni->HrefValue = "";
			$this->Dni->TooltipValue = "";

			// Nombre
			$this->Nombre->LinkCustomAttributes = "";
			$this->Nombre->HrefValue = "";
			$this->Nombre->TooltipValue = "";

			// Password
			$this->Password->LinkCustomAttributes = "";
			$this->Password->HrefValue = "";
			$this->Password->TooltipValue = "";

			// Nivel_Usuario
			$this->Nivel_Usuario->LinkCustomAttributes = "";
			$this->Nivel_Usuario->HrefValue = "";
			$this->Nivel_Usuario->TooltipValue = "";

			// Curso
			$this->Curso->LinkCustomAttributes = "";
			$this->Curso->HrefValue = "";
			$this->Curso->TooltipValue = "";

			// Turno
			$this->Turno->LinkCustomAttributes = "";
			$this->Turno->HrefValue = "";
			$this->Turno->TooltipValue = "";

			// Division
			$this->Division->LinkCustomAttributes = "";
			$this->Division->HrefValue = "";
			$this->Division->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// NombreTitular
			$this->NombreTitular->EditAttrs["class"] = "form-control";
			$this->NombreTitular->EditCustomAttributes = "";
			$this->NombreTitular->EditValue = ew_HtmlEncode($this->NombreTitular->CurrentValue);
			if (strval($this->NombreTitular->CurrentValue) <> "") {
				$sFilterWrk = "`Apellidos_Nombres`" . ew_SearchString("=", $this->NombreTitular->CurrentValue, EW_DATATYPE_MEMO, "");
			$sSqlWrk = "SELECT `Apellidos_Nombres`, `Apellidos_Nombres` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `personas`";
			$sWhereWrk = "";
			$this->NombreTitular->LookupFilters = array("dx1" => "`Apellidos_Nombres`");
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->NombreTitular, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->NombreTitular->EditValue = $this->NombreTitular->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->NombreTitular->EditValue = ew_HtmlEncode($this->NombreTitular->CurrentValue);
				}
			} else {
				$this->NombreTitular->EditValue = NULL;
			}
			$this->NombreTitular->PlaceHolder = ew_RemoveHtml($this->NombreTitular->FldCaption());

			// Dni
			$this->Dni->EditAttrs["class"] = "form-control";
			$this->Dni->EditCustomAttributes = "";
			$this->Dni->EditValue = ew_HtmlEncode($this->Dni->CurrentValue);
			$this->Dni->PlaceHolder = ew_RemoveHtml($this->Dni->FldCaption());

			// Nombre
			$this->Nombre->EditAttrs["class"] = "form-control";
			$this->Nombre->EditCustomAttributes = "";
			if (!$Security->IsAdmin() && $Security->IsLoggedIn() && !$this->UserIDAllow($this->CurrentAction)) { // Non system admin
				$this->Nombre->CurrentValue = CurrentUserID();
			$this->Nombre->EditValue = $this->Nombre->CurrentValue;
			$this->Nombre->ViewCustomAttributes = "";
			} else {
			$this->Nombre->EditValue = ew_HtmlEncode($this->Nombre->CurrentValue);
			$this->Nombre->PlaceHolder = ew_RemoveHtml($this->Nombre->FldCaption());
			}

			// Password
			$this->Password->EditAttrs["class"] = "form-control ewPasswordStrength";
			$this->Password->EditCustomAttributes = "";
			$this->Password->EditValue = ew_HtmlEncode($this->Password->CurrentValue);
			$this->Password->PlaceHolder = ew_RemoveHtml($this->Password->FldCaption());

			// Nivel_Usuario
			$this->Nivel_Usuario->EditAttrs["class"] = "form-control";
			$this->Nivel_Usuario->EditCustomAttributes = "";
			if (!$Security->CanAdmin()) { // System admin
				$this->Nivel_Usuario->EditValue = $Language->Phrase("PasswordMask");
			} else {
			if (trim(strval($this->Nivel_Usuario->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->Nivel_Usuario->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `userlevels`";
			$sWhereWrk = "";
			$this->Nivel_Usuario->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Nivel_Usuario, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->Nivel_Usuario->EditValue = $arwrk;
			}

			// Curso
			$this->Curso->EditAttrs["class"] = "form-control";
			$this->Curso->EditCustomAttributes = "";
			if (trim(strval($this->Curso->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id_Curso`" . ew_SearchString("=", $this->Curso->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `Id_Curso`, `Descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cursos`";
			$sWhereWrk = "";
			$this->Curso->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Curso, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->Curso->EditValue = $arwrk;

			// Turno
			$this->Turno->EditAttrs["class"] = "form-control";
			$this->Turno->EditCustomAttributes = "";
			if (trim(strval($this->Turno->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id_Turno`" . ew_SearchString("=", $this->Turno->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `Id_Turno`, `Descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `turno`";
			$sWhereWrk = "";
			$this->Turno->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Turno, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->Turno->EditValue = $arwrk;

			// Division
			$this->Division->EditAttrs["class"] = "form-control";
			$this->Division->EditCustomAttributes = "";
			if (trim(strval($this->Division->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id_Division`" . ew_SearchString("=", $this->Division->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `Id_Division`, `Descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `division`";
			$sWhereWrk = "";
			$this->Division->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Division, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->Division->EditValue = $arwrk;

			// Add refer script
			// NombreTitular

			$this->NombreTitular->LinkCustomAttributes = "";
			$this->NombreTitular->HrefValue = "";

			// Dni
			$this->Dni->LinkCustomAttributes = "";
			$this->Dni->HrefValue = "";

			// Nombre
			$this->Nombre->LinkCustomAttributes = "";
			$this->Nombre->HrefValue = "";

			// Password
			$this->Password->LinkCustomAttributes = "";
			$this->Password->HrefValue = "";

			// Nivel_Usuario
			$this->Nivel_Usuario->LinkCustomAttributes = "";
			$this->Nivel_Usuario->HrefValue = "";

			// Curso
			$this->Curso->LinkCustomAttributes = "";
			$this->Curso->HrefValue = "";

			// Turno
			$this->Turno->LinkCustomAttributes = "";
			$this->Turno->HrefValue = "";

			// Division
			$this->Division->LinkCustomAttributes = "";
			$this->Division->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// NombreTitular
			$this->NombreTitular->EditAttrs["class"] = "form-control";
			$this->NombreTitular->EditCustomAttributes = "";
			$this->NombreTitular->EditValue = ew_HtmlEncode($this->NombreTitular->CurrentValue);
			if (strval($this->NombreTitular->CurrentValue) <> "") {
				$sFilterWrk = "`Apellidos_Nombres`" . ew_SearchString("=", $this->NombreTitular->CurrentValue, EW_DATATYPE_MEMO, "");
			$sSqlWrk = "SELECT `Apellidos_Nombres`, `Apellidos_Nombres` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `personas`";
			$sWhereWrk = "";
			$this->NombreTitular->LookupFilters = array("dx1" => "`Apellidos_Nombres`");
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->NombreTitular, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->NombreTitular->EditValue = $this->NombreTitular->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->NombreTitular->EditValue = ew_HtmlEncode($this->NombreTitular->CurrentValue);
				}
			} else {
				$this->NombreTitular->EditValue = NULL;
			}
			$this->NombreTitular->PlaceHolder = ew_RemoveHtml($this->NombreTitular->FldCaption());

			// Dni
			$this->Dni->EditAttrs["class"] = "form-control";
			$this->Dni->EditCustomAttributes = "";
			$this->Dni->EditValue = ew_HtmlEncode($this->Dni->CurrentValue);
			$this->Dni->PlaceHolder = ew_RemoveHtml($this->Dni->FldCaption());

			// Nombre
			$this->Nombre->EditAttrs["class"] = "form-control";
			$this->Nombre->EditCustomAttributes = "";
			$this->Nombre->EditValue = $this->Nombre->CurrentValue;
			$this->Nombre->ViewCustomAttributes = "";

			// Password
			$this->Password->EditAttrs["class"] = "form-control ewPasswordStrength";
			$this->Password->EditCustomAttributes = "";
			$this->Password->EditValue = ew_HtmlEncode($this->Password->CurrentValue);
			$this->Password->PlaceHolder = ew_RemoveHtml($this->Password->FldCaption());

			// Nivel_Usuario
			$this->Nivel_Usuario->EditAttrs["class"] = "form-control";
			$this->Nivel_Usuario->EditCustomAttributes = "";
			if (!$Security->CanAdmin()) { // System admin
				$this->Nivel_Usuario->EditValue = $Language->Phrase("PasswordMask");
			} else {
			if (trim(strval($this->Nivel_Usuario->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->Nivel_Usuario->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `userlevels`";
			$sWhereWrk = "";
			$this->Nivel_Usuario->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Nivel_Usuario, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->Nivel_Usuario->EditValue = $arwrk;
			}

			// Curso
			$this->Curso->EditAttrs["class"] = "form-control";
			$this->Curso->EditCustomAttributes = "";
			if (trim(strval($this->Curso->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id_Curso`" . ew_SearchString("=", $this->Curso->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `Id_Curso`, `Descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cursos`";
			$sWhereWrk = "";
			$this->Curso->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Curso, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->Curso->EditValue = $arwrk;

			// Turno
			$this->Turno->EditAttrs["class"] = "form-control";
			$this->Turno->EditCustomAttributes = "";
			if (trim(strval($this->Turno->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id_Turno`" . ew_SearchString("=", $this->Turno->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `Id_Turno`, `Descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `turno`";
			$sWhereWrk = "";
			$this->Turno->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Turno, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->Turno->EditValue = $arwrk;

			// Division
			$this->Division->EditAttrs["class"] = "form-control";
			$this->Division->EditCustomAttributes = "";
			if (trim(strval($this->Division->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id_Division`" . ew_SearchString("=", $this->Division->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `Id_Division`, `Descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `division`";
			$sWhereWrk = "";
			$this->Division->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Division, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->Division->EditValue = $arwrk;

			// Edit refer script
			// NombreTitular

			$this->NombreTitular->LinkCustomAttributes = "";
			$this->NombreTitular->HrefValue = "";

			// Dni
			$this->Dni->LinkCustomAttributes = "";
			$this->Dni->HrefValue = "";

			// Nombre
			$this->Nombre->LinkCustomAttributes = "";
			$this->Nombre->HrefValue = "";

			// Password
			$this->Password->LinkCustomAttributes = "";
			$this->Password->HrefValue = "";

			// Nivel_Usuario
			$this->Nivel_Usuario->LinkCustomAttributes = "";
			$this->Nivel_Usuario->HrefValue = "";

			// Curso
			$this->Curso->LinkCustomAttributes = "";
			$this->Curso->HrefValue = "";

			// Turno
			$this->Turno->LinkCustomAttributes = "";
			$this->Turno->HrefValue = "";

			// Division
			$this->Division->LinkCustomAttributes = "";
			$this->Division->HrefValue = "";
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
		if (!ew_CheckInteger($this->Dni->FormValue)) {
			ew_AddMessage($gsFormError, $this->Dni->FldErrMsg());
		}
		if (!$this->Nombre->FldIsDetailKey && !is_null($this->Nombre->FormValue) && $this->Nombre->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Nombre->FldCaption(), $this->Nombre->ReqErrMsg));
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
				$sThisKey .= $row['Nombre'];
				$this->LoadDbValues($row);
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
			$rsnew = array();

			// NombreTitular
			$this->NombreTitular->SetDbValueDef($rsnew, $this->NombreTitular->CurrentValue, NULL, $this->NombreTitular->ReadOnly);

			// Dni
			$this->Dni->SetDbValueDef($rsnew, $this->Dni->CurrentValue, NULL, $this->Dni->ReadOnly);

			// Nombre
			// Password

			$this->Password->SetDbValueDef($rsnew, $this->Password->CurrentValue, NULL, $this->Password->ReadOnly || (EW_ENCRYPTED_PASSWORD && $rs->fields('Password') == $this->Password->CurrentValue));

			// Nivel_Usuario
			if ($Security->CanAdmin()) { // System admin
			$this->Nivel_Usuario->SetDbValueDef($rsnew, $this->Nivel_Usuario->CurrentValue, NULL, $this->Nivel_Usuario->ReadOnly);
			}

			// Curso
			$this->Curso->SetDbValueDef($rsnew, $this->Curso->CurrentValue, NULL, $this->Curso->ReadOnly);

			// Turno
			$this->Turno->SetDbValueDef($rsnew, $this->Turno->CurrentValue, NULL, $this->Turno->ReadOnly);

			// Division
			$this->Division->SetDbValueDef($rsnew, $this->Division->CurrentValue, NULL, $this->Division->ReadOnly);

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

		// Check if valid User ID
		$bValidUser = FALSE;
		if ($Security->CurrentUserID() <> "" && !ew_Empty($this->Nombre->CurrentValue) && !$Security->IsAdmin()) { // Non system admin
			$bValidUser = $Security->IsValidUserID($this->Nombre->CurrentValue);
			if (!$bValidUser) {
				$sUserIdMsg = str_replace("%c", CurrentUserID(), $Language->Phrase("UnAuthorizedUserID"));
				$sUserIdMsg = str_replace("%u", $this->Nombre->CurrentValue, $sUserIdMsg);
				$this->setFailureMessage($sUserIdMsg);
				return FALSE;
			}
		}
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// NombreTitular
		$this->NombreTitular->SetDbValueDef($rsnew, $this->NombreTitular->CurrentValue, NULL, FALSE);

		// Dni
		$this->Dni->SetDbValueDef($rsnew, $this->Dni->CurrentValue, NULL, FALSE);

		// Nombre
		$this->Nombre->SetDbValueDef($rsnew, $this->Nombre->CurrentValue, "", FALSE);

		// Password
		$this->Password->SetDbValueDef($rsnew, $this->Password->CurrentValue, NULL, FALSE);

		// Nivel_Usuario
		if ($Security->CanAdmin()) { // System admin
		$this->Nivel_Usuario->SetDbValueDef($rsnew, $this->Nivel_Usuario->CurrentValue, NULL, FALSE);
		}

		// Curso
		$this->Curso->SetDbValueDef($rsnew, $this->Curso->CurrentValue, NULL, FALSE);

		// Turno
		$this->Turno->SetDbValueDef($rsnew, $this->Turno->CurrentValue, NULL, FALSE);

		// Division
		$this->Division->SetDbValueDef($rsnew, $this->Division->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['Nombre']) == "") {
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
		$this->NombreTitular->AdvancedSearch->Load();
		$this->Dni->AdvancedSearch->Load();
		$this->Nombre->AdvancedSearch->Load();
		$this->Password->AdvancedSearch->Load();
		$this->Nivel_Usuario->AdvancedSearch->Load();
		$this->Curso->AdvancedSearch->Load();
		$this->Turno->AdvancedSearch->Load();
		$this->Division->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_usuarios\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_usuarios',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fusuarioslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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

	// Show link optionally based on User ID
	function ShowOptionLink($id = "") {
		global $Security;
		if ($Security->IsLoggedIn() && !$Security->IsAdmin() && !$this->UserIDAllow($id))
			return $Security->IsValidUserID($this->Nombre->CurrentValue);
		return TRUE;
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
		case "x_NombreTitular":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Apellidos_Nombres` AS `LinkFld`, `Apellidos_Nombres` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `personas`";
			$sWhereWrk = "{filter}";
			$this->NombreTitular->LookupFilters = array("dx1" => "`Apellidos_Nombres`");
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`Apellidos_Nombres` = {filter_value}", "t0" => "201", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->NombreTitular, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_Nivel_Usuario":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `userlevelid` AS `LinkFld`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `userlevels`";
			$sWhereWrk = "";
			$this->Nivel_Usuario->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`userlevelid` = {filter_value}", "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Nivel_Usuario, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_Curso":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Id_Curso` AS `LinkFld`, `Descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cursos`";
			$sWhereWrk = "";
			$this->Curso->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`Id_Curso` = {filter_value}", "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Curso, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_Turno":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Id_Turno` AS `LinkFld`, `Descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `turno`";
			$sWhereWrk = "";
			$this->Turno->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`Id_Turno` = {filter_value}", "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Turno, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_Division":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Id_Division` AS `LinkFld`, `Descripcion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `division`";
			$sWhereWrk = "";
			$this->Division->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => "`Id_Division` = {filter_value}", "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Division, $sWhereWrk); // Call Lookup selecting
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
		case "x_NombreTitular":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Apellidos_Nombres`, `Apellidos_Nombres` AS `DispFld` FROM `personas`";
			$sWhereWrk = "`Apellidos_Nombres` LIKE '{query_value}%'";
			$this->NombreTitular->LookupFilters = array("dx1" => "`Apellidos_Nombres`");
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->NombreTitular, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		}
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'usuarios';
		$usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnAdd) return;
		$table = 'usuarios';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['Nombre'];

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
				if ($fldname == 'Password')
					$newvalue = $Language->Phrase("PasswordMask");
				ew_WriteAuditTrail("log", $dt, $id, $usr, "A", $table, $fldname, $key, "", $newvalue);
			}
		}
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		global $Language;
		if (!$this->AuditTrailOnEdit) return;
		$table = 'usuarios';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['Nombre'];

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
					if ($fldname == 'Password') {
						$oldvalue = $Language->Phrase("PasswordMask");
						$newvalue = $Language->Phrase("PasswordMask");
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
		$table = 'usuarios';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['Nombre'];

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
				if ($fldname == 'Password')
					$oldvalue = $Language->Phrase("PasswordMask");
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
if (!isset($usuarios_list)) $usuarios_list = new cusuarios_list();

// Page init
$usuarios_list->Page_Init();

// Page main
$usuarios_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$usuarios_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($usuarios->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fusuarioslist = new ew_Form("fusuarioslist", "list");
fusuarioslist.FormKeyCountName = '<?php echo $usuarios_list->FormKeyCountName ?>';

// Validate form
fusuarioslist.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_Dni");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($usuarios->Dni->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Nombre");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $usuarios->Nombre->FldCaption(), $usuarios->Nombre->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Password");
			if (elm && $(elm).hasClass("ewPasswordStrength") && !$(elm).data("validated"))
				return this.OnError(elm, ewLanguage.Phrase("PasswordTooSimple"));

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
fusuarioslist.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "NombreTitular", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Dni", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Nombre", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Password", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Nivel_Usuario", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Curso", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Turno", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Division", false)) return false;
	return true;
}

// Form_CustomValidate event
fusuarioslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fusuarioslist.ValidateRequired = true;
<?php } else { ?>
fusuarioslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fusuarioslist.Lists["x_NombreTitular"] = {"LinkField":"x_Apellidos_Nombres","Ajax":true,"AutoFill":true,"DisplayFields":["x_Apellidos_Nombres","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"personas"};
fusuarioslist.Lists["x_Nivel_Usuario"] = {"LinkField":"x_userlevelid","Ajax":true,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"userlevels"};
fusuarioslist.Lists["x_Curso"] = {"LinkField":"x_Id_Curso","Ajax":true,"AutoFill":false,"DisplayFields":["x_Descripcion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"cursos"};
fusuarioslist.Lists["x_Turno"] = {"LinkField":"x_Id_Turno","Ajax":true,"AutoFill":false,"DisplayFields":["x_Descripcion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"turno"};
fusuarioslist.Lists["x_Division"] = {"LinkField":"x_Id_Division","Ajax":true,"AutoFill":false,"DisplayFields":["x_Descripcion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"division"};

// Form object for search
var CurrentSearchForm = fusuarioslistsrch = new ew_Form("fusuarioslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($usuarios->Export == "") { ?>
<div class="ewToolbar">
<?php if ($usuarios->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($usuarios_list->TotalRecs > 0 && $usuarios_list->ExportOptions->Visible()) { ?>
<?php $usuarios_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($usuarios_list->SearchOptions->Visible()) { ?>
<?php $usuarios_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($usuarios_list->FilterOptions->Visible()) { ?>
<?php $usuarios_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php if ($usuarios->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
if ($usuarios->CurrentAction == "gridadd") {
	$usuarios->CurrentFilter = "0=1";
	$usuarios_list->StartRec = 1;
	$usuarios_list->DisplayRecs = $usuarios->GridAddRowCount;
	$usuarios_list->TotalRecs = $usuarios_list->DisplayRecs;
	$usuarios_list->StopRec = $usuarios_list->DisplayRecs;
} else {
	$bSelectLimit = $usuarios_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($usuarios_list->TotalRecs <= 0)
			$usuarios_list->TotalRecs = $usuarios->SelectRecordCount();
	} else {
		if (!$usuarios_list->Recordset && ($usuarios_list->Recordset = $usuarios_list->LoadRecordset()))
			$usuarios_list->TotalRecs = $usuarios_list->Recordset->RecordCount();
	}
	$usuarios_list->StartRec = 1;
	if ($usuarios_list->DisplayRecs <= 0 || ($usuarios->Export <> "" && $usuarios->ExportAll)) // Display all records
		$usuarios_list->DisplayRecs = $usuarios_list->TotalRecs;
	if (!($usuarios->Export <> "" && $usuarios->ExportAll))
		$usuarios_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$usuarios_list->Recordset = $usuarios_list->LoadRecordset($usuarios_list->StartRec-1, $usuarios_list->DisplayRecs);

	// Set no record found message
	if ($usuarios->CurrentAction == "" && $usuarios_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$usuarios_list->setWarningMessage(ew_DeniedMsg());
		if ($usuarios_list->SearchWhere == "0=101")
			$usuarios_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$usuarios_list->setWarningMessage($Language->Phrase("NoRecord"));
	}

	// Audit trail on search
	if ($usuarios_list->AuditTrailOnSearch && $usuarios_list->Command == "search" && !$usuarios_list->RestoreSearch) {
		$searchparm = ew_ServerVar("QUERY_STRING");
		$searchsql = $usuarios_list->getSessionWhere();
		$usuarios_list->WriteAuditTrailOnSearch($searchparm, $searchsql);
	}
}
$usuarios_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($usuarios->Export == "" && $usuarios->CurrentAction == "") { ?>
<form name="fusuarioslistsrch" id="fusuarioslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($usuarios_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fusuarioslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="usuarios">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($usuarios_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($usuarios_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $usuarios_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($usuarios_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($usuarios_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($usuarios_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($usuarios_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $usuarios_list->ShowPageHeader(); ?>
<?php
$usuarios_list->ShowMessage();
?>
<?php if ($usuarios_list->TotalRecs > 0 || $usuarios->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid usuarios">
<?php if ($usuarios->Export == "") { ?>
<div class="panel-heading ewGridUpperPanel">
<?php if ($usuarios->CurrentAction <> "gridadd" && $usuarios->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($usuarios_list->Pager)) $usuarios_list->Pager = new cPrevNextPager($usuarios_list->StartRec, $usuarios_list->DisplayRecs, $usuarios_list->TotalRecs) ?>
<?php if ($usuarios_list->Pager->RecordCount > 0 && $usuarios_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($usuarios_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $usuarios_list->PageUrl() ?>start=<?php echo $usuarios_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($usuarios_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $usuarios_list->PageUrl() ?>start=<?php echo $usuarios_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $usuarios_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($usuarios_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $usuarios_list->PageUrl() ?>start=<?php echo $usuarios_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($usuarios_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $usuarios_list->PageUrl() ?>start=<?php echo $usuarios_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $usuarios_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $usuarios_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $usuarios_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $usuarios_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($usuarios_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fusuarioslist" id="fusuarioslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($usuarios_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $usuarios_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="usuarios">
<div id="gmp_usuarios" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($usuarios_list->TotalRecs > 0 || $usuarios->CurrentAction == "add" || $usuarios->CurrentAction == "copy") { ?>
<table id="tbl_usuarioslist" class="table ewTable">
<?php echo $usuarios->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$usuarios_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$usuarios_list->RenderListOptions();

// Render list options (header, left)
$usuarios_list->ListOptions->Render("header", "left");
?>
<?php if ($usuarios->NombreTitular->Visible) { // NombreTitular ?>
	<?php if ($usuarios->SortUrl($usuarios->NombreTitular) == "") { ?>
		<th data-name="NombreTitular"><div id="elh_usuarios_NombreTitular" class="usuarios_NombreTitular"><div class="ewTableHeaderCaption"><?php echo $usuarios->NombreTitular->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NombreTitular"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $usuarios->SortUrl($usuarios->NombreTitular) ?>',1);"><div id="elh_usuarios_NombreTitular" class="usuarios_NombreTitular">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $usuarios->NombreTitular->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($usuarios->NombreTitular->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($usuarios->NombreTitular->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($usuarios->Dni->Visible) { // Dni ?>
	<?php if ($usuarios->SortUrl($usuarios->Dni) == "") { ?>
		<th data-name="Dni"><div id="elh_usuarios_Dni" class="usuarios_Dni"><div class="ewTableHeaderCaption"><?php echo $usuarios->Dni->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Dni"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $usuarios->SortUrl($usuarios->Dni) ?>',1);"><div id="elh_usuarios_Dni" class="usuarios_Dni">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $usuarios->Dni->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($usuarios->Dni->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($usuarios->Dni->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($usuarios->Nombre->Visible) { // Nombre ?>
	<?php if ($usuarios->SortUrl($usuarios->Nombre) == "") { ?>
		<th data-name="Nombre"><div id="elh_usuarios_Nombre" class="usuarios_Nombre"><div class="ewTableHeaderCaption"><?php echo $usuarios->Nombre->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Nombre"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $usuarios->SortUrl($usuarios->Nombre) ?>',1);"><div id="elh_usuarios_Nombre" class="usuarios_Nombre">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $usuarios->Nombre->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($usuarios->Nombre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($usuarios->Nombre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($usuarios->Password->Visible) { // Password ?>
	<?php if ($usuarios->SortUrl($usuarios->Password) == "") { ?>
		<th data-name="Password"><div id="elh_usuarios_Password" class="usuarios_Password"><div class="ewTableHeaderCaption"><?php echo $usuarios->Password->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Password"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $usuarios->SortUrl($usuarios->Password) ?>',1);"><div id="elh_usuarios_Password" class="usuarios_Password">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $usuarios->Password->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($usuarios->Password->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($usuarios->Password->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($usuarios->Nivel_Usuario->Visible) { // Nivel_Usuario ?>
	<?php if ($usuarios->SortUrl($usuarios->Nivel_Usuario) == "") { ?>
		<th data-name="Nivel_Usuario"><div id="elh_usuarios_Nivel_Usuario" class="usuarios_Nivel_Usuario"><div class="ewTableHeaderCaption"><?php echo $usuarios->Nivel_Usuario->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Nivel_Usuario"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $usuarios->SortUrl($usuarios->Nivel_Usuario) ?>',1);"><div id="elh_usuarios_Nivel_Usuario" class="usuarios_Nivel_Usuario">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $usuarios->Nivel_Usuario->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($usuarios->Nivel_Usuario->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($usuarios->Nivel_Usuario->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($usuarios->Curso->Visible) { // Curso ?>
	<?php if ($usuarios->SortUrl($usuarios->Curso) == "") { ?>
		<th data-name="Curso"><div id="elh_usuarios_Curso" class="usuarios_Curso"><div class="ewTableHeaderCaption"><?php echo $usuarios->Curso->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Curso"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $usuarios->SortUrl($usuarios->Curso) ?>',1);"><div id="elh_usuarios_Curso" class="usuarios_Curso">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $usuarios->Curso->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($usuarios->Curso->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($usuarios->Curso->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($usuarios->Turno->Visible) { // Turno ?>
	<?php if ($usuarios->SortUrl($usuarios->Turno) == "") { ?>
		<th data-name="Turno"><div id="elh_usuarios_Turno" class="usuarios_Turno"><div class="ewTableHeaderCaption"><?php echo $usuarios->Turno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Turno"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $usuarios->SortUrl($usuarios->Turno) ?>',1);"><div id="elh_usuarios_Turno" class="usuarios_Turno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $usuarios->Turno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($usuarios->Turno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($usuarios->Turno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($usuarios->Division->Visible) { // Division ?>
	<?php if ($usuarios->SortUrl($usuarios->Division) == "") { ?>
		<th data-name="Division"><div id="elh_usuarios_Division" class="usuarios_Division"><div class="ewTableHeaderCaption"><?php echo $usuarios->Division->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Division"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $usuarios->SortUrl($usuarios->Division) ?>',1);"><div id="elh_usuarios_Division" class="usuarios_Division">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $usuarios->Division->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($usuarios->Division->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($usuarios->Division->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$usuarios_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
	if ($usuarios->CurrentAction == "add" || $usuarios->CurrentAction == "copy") {
		$usuarios_list->RowIndex = 0;
		$usuarios_list->KeyCount = $usuarios_list->RowIndex;
		if ($usuarios->CurrentAction == "add")
			$usuarios_list->LoadDefaultValues();
		if ($usuarios->EventCancelled) // Insert failed
			$usuarios_list->RestoreFormValues(); // Restore form values

		// Set row properties
		$usuarios->ResetAttrs();
		$usuarios->RowAttrs = array_merge($usuarios->RowAttrs, array('data-rowindex'=>0, 'id'=>'r0_usuarios', 'data-rowtype'=>EW_ROWTYPE_ADD));
		$usuarios->RowType = EW_ROWTYPE_ADD;

		// Render row
		$usuarios_list->RenderRow();

		// Render list options
		$usuarios_list->RenderListOptions();
		$usuarios_list->StartRowCnt = 0;
?>
	<tr<?php echo $usuarios->RowAttributes() ?>>
<?php

// Render list options (body, left)
$usuarios_list->ListOptions->Render("body", "left", $usuarios_list->RowCnt);
?>
	<?php if ($usuarios->NombreTitular->Visible) { // NombreTitular ?>
		<td data-name="NombreTitular">
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_NombreTitular" class="form-group usuarios_NombreTitular">
<?php $usuarios->NombreTitular->EditAttrs["onchange"] = "ew_AutoFill(this); " . @$usuarios->NombreTitular->EditAttrs["onchange"]; ?>
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $usuarios_list->RowIndex ?>_NombreTitular"><?php echo (strval($usuarios->NombreTitular->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $usuarios->NombreTitular->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($usuarios->NombreTitular->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $usuarios_list->RowIndex ?>_NombreTitular',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="usuarios" data-field="x_NombreTitular" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $usuarios->NombreTitular->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $usuarios_list->RowIndex ?>_NombreTitular" id="x<?php echo $usuarios_list->RowIndex ?>_NombreTitular" value="<?php echo $usuarios->NombreTitular->CurrentValue ?>"<?php echo $usuarios->NombreTitular->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $usuarios_list->RowIndex ?>_NombreTitular" id="s_x<?php echo $usuarios_list->RowIndex ?>_NombreTitular" value="<?php echo $usuarios->NombreTitular->LookupFilterQuery() ?>">
<input type="hidden" name="ln_x<?php echo $usuarios_list->RowIndex ?>_NombreTitular" id="ln_x<?php echo $usuarios_list->RowIndex ?>_NombreTitular" value="x<?php echo $usuarios_list->RowIndex ?>_Dni,x<?php echo $usuarios_list->RowIndex ?>_Curso,x<?php echo $usuarios_list->RowIndex ?>_Turno,x<?php echo $usuarios_list->RowIndex ?>_Division">
</span>
<input type="hidden" data-table="usuarios" data-field="x_NombreTitular" name="o<?php echo $usuarios_list->RowIndex ?>_NombreTitular" id="o<?php echo $usuarios_list->RowIndex ?>_NombreTitular" value="<?php echo ew_HtmlEncode($usuarios->NombreTitular->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($usuarios->Dni->Visible) { // Dni ?>
		<td data-name="Dni">
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Dni" class="form-group usuarios_Dni">
<input type="text" data-table="usuarios" data-field="x_Dni" name="x<?php echo $usuarios_list->RowIndex ?>_Dni" id="x<?php echo $usuarios_list->RowIndex ?>_Dni" size="30" placeholder="<?php echo ew_HtmlEncode($usuarios->Dni->getPlaceHolder()) ?>" value="<?php echo $usuarios->Dni->EditValue ?>"<?php echo $usuarios->Dni->EditAttributes() ?>>
</span>
<input type="hidden" data-table="usuarios" data-field="x_Dni" name="o<?php echo $usuarios_list->RowIndex ?>_Dni" id="o<?php echo $usuarios_list->RowIndex ?>_Dni" value="<?php echo ew_HtmlEncode($usuarios->Dni->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($usuarios->Nombre->Visible) { // Nombre ?>
		<td data-name="Nombre">
<?php if (!$Security->IsAdmin() && $Security->IsLoggedIn() && !$usuarios->UserIDAllow($usuarios->CurrentAction)) { // Non system admin ?>
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Nombre" class="form-group usuarios_Nombre">
<span<?php echo $usuarios->Nombre->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $usuarios->Nombre->EditValue ?></p></span>
</span>
<input type="hidden" data-table="usuarios" data-field="x_Nombre" name="x<?php echo $usuarios_list->RowIndex ?>_Nombre" id="x<?php echo $usuarios_list->RowIndex ?>_Nombre" value="<?php echo ew_HtmlEncode($usuarios->Nombre->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Nombre" class="form-group usuarios_Nombre">
<input type="text" data-table="usuarios" data-field="x_Nombre" name="x<?php echo $usuarios_list->RowIndex ?>_Nombre" id="x<?php echo $usuarios_list->RowIndex ?>_Nombre" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($usuarios->Nombre->getPlaceHolder()) ?>" value="<?php echo $usuarios->Nombre->EditValue ?>"<?php echo $usuarios->Nombre->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="usuarios" data-field="x_Nombre" name="o<?php echo $usuarios_list->RowIndex ?>_Nombre" id="o<?php echo $usuarios_list->RowIndex ?>_Nombre" value="<?php echo ew_HtmlEncode($usuarios->Nombre->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($usuarios->Password->Visible) { // Password ?>
		<td data-name="Password">
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Password" class="form-group usuarios_Password">
<div class="input-group" id="ig<?php echo $usuarios_list->RowIndex ?>_Password">
<input type="text" data-password-strength="pst<?php echo $usuarios_list->RowIndex ?>_Password" data-password-generated="pgt<?php echo $usuarios_list->RowIndex ?>_Password" data-table="usuarios" data-field="x_Password" name="x<?php echo $usuarios_list->RowIndex ?>_Password" id="x<?php echo $usuarios_list->RowIndex ?>_Password" value="<?php echo $usuarios->Password->EditValue ?>" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($usuarios->Password->getPlaceHolder()) ?>"<?php echo $usuarios->Password->EditAttributes() ?>>
<span class="input-group-btn">
	<button type="button" class="btn btn-default ewPasswordGenerator" title="<?php echo ew_HtmlTitle($Language->Phrase("GeneratePassword")) ?>" data-password-field="x<?php echo $usuarios_list->RowIndex ?>_Password" data-password-confirm="c<?php echo $usuarios_list->RowIndex ?>_Password" data-password-strength="pst<?php echo $usuarios_list->RowIndex ?>_Password" data-password-generated="pgt<?php echo $usuarios_list->RowIndex ?>_Password"><?php echo $Language->Phrase("GeneratePassword") ?></button>
</span>
</div>
<span class="help-block" id="pgt<?php echo $usuarios_list->RowIndex ?>_Password" style="display: none;"></span>
<div class="progress ewPasswordStrengthBar" id="pst<?php echo $usuarios_list->RowIndex ?>_Password" style="display: none;">
	<div class="progress-bar" role="progressbar"></div>
</div>
</span>
<input type="hidden" data-table="usuarios" data-field="x_Password" name="o<?php echo $usuarios_list->RowIndex ?>_Password" id="o<?php echo $usuarios_list->RowIndex ?>_Password" value="<?php echo ew_HtmlEncode($usuarios->Password->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($usuarios->Nivel_Usuario->Visible) { // Nivel_Usuario ?>
		<td data-name="Nivel_Usuario">
<?php if (!$Security->IsAdmin() && $Security->IsLoggedIn()) { // Non system admin ?>
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Nivel_Usuario" class="form-group usuarios_Nivel_Usuario">
<p class="form-control-static"><?php echo $usuarios->Nivel_Usuario->EditValue ?></p>
</span>
<?php } else { ?>
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Nivel_Usuario" class="form-group usuarios_Nivel_Usuario">
<select data-table="usuarios" data-field="x_Nivel_Usuario" data-value-separator="<?php echo $usuarios->Nivel_Usuario->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $usuarios_list->RowIndex ?>_Nivel_Usuario" name="x<?php echo $usuarios_list->RowIndex ?>_Nivel_Usuario"<?php echo $usuarios->Nivel_Usuario->EditAttributes() ?>>
<?php echo $usuarios->Nivel_Usuario->SelectOptionListHtml("x<?php echo $usuarios_list->RowIndex ?>_Nivel_Usuario") ?>
</select>
<input type="hidden" name="s_x<?php echo $usuarios_list->RowIndex ?>_Nivel_Usuario" id="s_x<?php echo $usuarios_list->RowIndex ?>_Nivel_Usuario" value="<?php echo $usuarios->Nivel_Usuario->LookupFilterQuery() ?>">
</span>
<?php } ?>
<input type="hidden" data-table="usuarios" data-field="x_Nivel_Usuario" name="o<?php echo $usuarios_list->RowIndex ?>_Nivel_Usuario" id="o<?php echo $usuarios_list->RowIndex ?>_Nivel_Usuario" value="<?php echo ew_HtmlEncode($usuarios->Nivel_Usuario->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($usuarios->Curso->Visible) { // Curso ?>
		<td data-name="Curso">
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Curso" class="form-group usuarios_Curso">
<select data-table="usuarios" data-field="x_Curso" data-value-separator="<?php echo $usuarios->Curso->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $usuarios_list->RowIndex ?>_Curso" name="x<?php echo $usuarios_list->RowIndex ?>_Curso"<?php echo $usuarios->Curso->EditAttributes() ?>>
<?php echo $usuarios->Curso->SelectOptionListHtml("x<?php echo $usuarios_list->RowIndex ?>_Curso") ?>
</select>
<input type="hidden" name="s_x<?php echo $usuarios_list->RowIndex ?>_Curso" id="s_x<?php echo $usuarios_list->RowIndex ?>_Curso" value="<?php echo $usuarios->Curso->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="usuarios" data-field="x_Curso" name="o<?php echo $usuarios_list->RowIndex ?>_Curso" id="o<?php echo $usuarios_list->RowIndex ?>_Curso" value="<?php echo ew_HtmlEncode($usuarios->Curso->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($usuarios->Turno->Visible) { // Turno ?>
		<td data-name="Turno">
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Turno" class="form-group usuarios_Turno">
<select data-table="usuarios" data-field="x_Turno" data-value-separator="<?php echo $usuarios->Turno->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $usuarios_list->RowIndex ?>_Turno" name="x<?php echo $usuarios_list->RowIndex ?>_Turno"<?php echo $usuarios->Turno->EditAttributes() ?>>
<?php echo $usuarios->Turno->SelectOptionListHtml("x<?php echo $usuarios_list->RowIndex ?>_Turno") ?>
</select>
<input type="hidden" name="s_x<?php echo $usuarios_list->RowIndex ?>_Turno" id="s_x<?php echo $usuarios_list->RowIndex ?>_Turno" value="<?php echo $usuarios->Turno->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="usuarios" data-field="x_Turno" name="o<?php echo $usuarios_list->RowIndex ?>_Turno" id="o<?php echo $usuarios_list->RowIndex ?>_Turno" value="<?php echo ew_HtmlEncode($usuarios->Turno->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($usuarios->Division->Visible) { // Division ?>
		<td data-name="Division">
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Division" class="form-group usuarios_Division">
<select data-table="usuarios" data-field="x_Division" data-value-separator="<?php echo $usuarios->Division->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $usuarios_list->RowIndex ?>_Division" name="x<?php echo $usuarios_list->RowIndex ?>_Division"<?php echo $usuarios->Division->EditAttributes() ?>>
<?php echo $usuarios->Division->SelectOptionListHtml("x<?php echo $usuarios_list->RowIndex ?>_Division") ?>
</select>
<input type="hidden" name="s_x<?php echo $usuarios_list->RowIndex ?>_Division" id="s_x<?php echo $usuarios_list->RowIndex ?>_Division" value="<?php echo $usuarios->Division->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="usuarios" data-field="x_Division" name="o<?php echo $usuarios_list->RowIndex ?>_Division" id="o<?php echo $usuarios_list->RowIndex ?>_Division" value="<?php echo ew_HtmlEncode($usuarios->Division->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$usuarios_list->ListOptions->Render("body", "right", $usuarios_list->RowCnt);
?>
<script type="text/javascript">
fusuarioslist.UpdateOpts(<?php echo $usuarios_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
<?php
if ($usuarios->ExportAll && $usuarios->Export <> "") {
	$usuarios_list->StopRec = $usuarios_list->TotalRecs;
} else {

	// Set the last record to display
	if ($usuarios_list->TotalRecs > $usuarios_list->StartRec + $usuarios_list->DisplayRecs - 1)
		$usuarios_list->StopRec = $usuarios_list->StartRec + $usuarios_list->DisplayRecs - 1;
	else
		$usuarios_list->StopRec = $usuarios_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($usuarios_list->FormKeyCountName) && ($usuarios->CurrentAction == "gridadd" || $usuarios->CurrentAction == "gridedit" || $usuarios->CurrentAction == "F")) {
		$usuarios_list->KeyCount = $objForm->GetValue($usuarios_list->FormKeyCountName);
		$usuarios_list->StopRec = $usuarios_list->StartRec + $usuarios_list->KeyCount - 1;
	}
}
$usuarios_list->RecCnt = $usuarios_list->StartRec - 1;
if ($usuarios_list->Recordset && !$usuarios_list->Recordset->EOF) {
	$usuarios_list->Recordset->MoveFirst();
	$bSelectLimit = $usuarios_list->UseSelectLimit;
	if (!$bSelectLimit && $usuarios_list->StartRec > 1)
		$usuarios_list->Recordset->Move($usuarios_list->StartRec - 1);
} elseif (!$usuarios->AllowAddDeleteRow && $usuarios_list->StopRec == 0) {
	$usuarios_list->StopRec = $usuarios->GridAddRowCount;
}

// Initialize aggregate
$usuarios->RowType = EW_ROWTYPE_AGGREGATEINIT;
$usuarios->ResetAttrs();
$usuarios_list->RenderRow();
$usuarios_list->EditRowCnt = 0;
if ($usuarios->CurrentAction == "edit")
	$usuarios_list->RowIndex = 1;
if ($usuarios->CurrentAction == "gridadd")
	$usuarios_list->RowIndex = 0;
if ($usuarios->CurrentAction == "gridedit")
	$usuarios_list->RowIndex = 0;
while ($usuarios_list->RecCnt < $usuarios_list->StopRec) {
	$usuarios_list->RecCnt++;
	if (intval($usuarios_list->RecCnt) >= intval($usuarios_list->StartRec)) {
		$usuarios_list->RowCnt++;
		if ($usuarios->CurrentAction == "gridadd" || $usuarios->CurrentAction == "gridedit" || $usuarios->CurrentAction == "F") {
			$usuarios_list->RowIndex++;
			$objForm->Index = $usuarios_list->RowIndex;
			if ($objForm->HasValue($usuarios_list->FormActionName))
				$usuarios_list->RowAction = strval($objForm->GetValue($usuarios_list->FormActionName));
			elseif ($usuarios->CurrentAction == "gridadd")
				$usuarios_list->RowAction = "insert";
			else
				$usuarios_list->RowAction = "";
		}

		// Set up key count
		$usuarios_list->KeyCount = $usuarios_list->RowIndex;

		// Init row class and style
		$usuarios->ResetAttrs();
		$usuarios->CssClass = "";
		if ($usuarios->CurrentAction == "gridadd") {
			$usuarios_list->LoadDefaultValues(); // Load default values
		} else {
			$usuarios_list->LoadRowValues($usuarios_list->Recordset); // Load row values
		}
		$usuarios->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($usuarios->CurrentAction == "gridadd") // Grid add
			$usuarios->RowType = EW_ROWTYPE_ADD; // Render add
		if ($usuarios->CurrentAction == "gridadd" && $usuarios->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$usuarios_list->RestoreCurrentRowFormValues($usuarios_list->RowIndex); // Restore form values
		if ($usuarios->CurrentAction == "edit") {
			if ($usuarios_list->CheckInlineEditKey() && $usuarios_list->EditRowCnt == 0) { // Inline edit
				$usuarios->RowType = EW_ROWTYPE_EDIT; // Render edit
			}
		}
		if ($usuarios->CurrentAction == "gridedit") { // Grid edit
			if ($usuarios->EventCancelled) {
				$usuarios_list->RestoreCurrentRowFormValues($usuarios_list->RowIndex); // Restore form values
			}
			if ($usuarios_list->RowAction == "insert")
				$usuarios->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$usuarios->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($usuarios->CurrentAction == "edit" && $usuarios->RowType == EW_ROWTYPE_EDIT && $usuarios->EventCancelled) { // Update failed
			$objForm->Index = 1;
			$usuarios_list->RestoreFormValues(); // Restore form values
		}
		if ($usuarios->CurrentAction == "gridedit" && ($usuarios->RowType == EW_ROWTYPE_EDIT || $usuarios->RowType == EW_ROWTYPE_ADD) && $usuarios->EventCancelled) // Update failed
			$usuarios_list->RestoreCurrentRowFormValues($usuarios_list->RowIndex); // Restore form values
		if ($usuarios->RowType == EW_ROWTYPE_EDIT) // Edit row
			$usuarios_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$usuarios->RowAttrs = array_merge($usuarios->RowAttrs, array('data-rowindex'=>$usuarios_list->RowCnt, 'id'=>'r' . $usuarios_list->RowCnt . '_usuarios', 'data-rowtype'=>$usuarios->RowType));

		// Render row
		$usuarios_list->RenderRow();

		// Render list options
		$usuarios_list->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($usuarios_list->RowAction <> "delete" && $usuarios_list->RowAction <> "insertdelete" && !($usuarios_list->RowAction == "insert" && $usuarios->CurrentAction == "F" && $usuarios_list->EmptyRow())) {
?>
	<tr<?php echo $usuarios->RowAttributes() ?>>
<?php

// Render list options (body, left)
$usuarios_list->ListOptions->Render("body", "left", $usuarios_list->RowCnt);
?>
	<?php if ($usuarios->NombreTitular->Visible) { // NombreTitular ?>
		<td data-name="NombreTitular"<?php echo $usuarios->NombreTitular->CellAttributes() ?>>
<?php if ($usuarios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_NombreTitular" class="form-group usuarios_NombreTitular">
<?php $usuarios->NombreTitular->EditAttrs["onchange"] = "ew_AutoFill(this); " . @$usuarios->NombreTitular->EditAttrs["onchange"]; ?>
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $usuarios_list->RowIndex ?>_NombreTitular"><?php echo (strval($usuarios->NombreTitular->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $usuarios->NombreTitular->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($usuarios->NombreTitular->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $usuarios_list->RowIndex ?>_NombreTitular',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="usuarios" data-field="x_NombreTitular" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $usuarios->NombreTitular->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $usuarios_list->RowIndex ?>_NombreTitular" id="x<?php echo $usuarios_list->RowIndex ?>_NombreTitular" value="<?php echo $usuarios->NombreTitular->CurrentValue ?>"<?php echo $usuarios->NombreTitular->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $usuarios_list->RowIndex ?>_NombreTitular" id="s_x<?php echo $usuarios_list->RowIndex ?>_NombreTitular" value="<?php echo $usuarios->NombreTitular->LookupFilterQuery() ?>">
<input type="hidden" name="ln_x<?php echo $usuarios_list->RowIndex ?>_NombreTitular" id="ln_x<?php echo $usuarios_list->RowIndex ?>_NombreTitular" value="x<?php echo $usuarios_list->RowIndex ?>_Dni,x<?php echo $usuarios_list->RowIndex ?>_Curso,x<?php echo $usuarios_list->RowIndex ?>_Turno,x<?php echo $usuarios_list->RowIndex ?>_Division">
</span>
<input type="hidden" data-table="usuarios" data-field="x_NombreTitular" name="o<?php echo $usuarios_list->RowIndex ?>_NombreTitular" id="o<?php echo $usuarios_list->RowIndex ?>_NombreTitular" value="<?php echo ew_HtmlEncode($usuarios->NombreTitular->OldValue) ?>">
<?php } ?>
<?php if ($usuarios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_NombreTitular" class="form-group usuarios_NombreTitular">
<?php $usuarios->NombreTitular->EditAttrs["onchange"] = "ew_AutoFill(this); " . @$usuarios->NombreTitular->EditAttrs["onchange"]; ?>
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $usuarios_list->RowIndex ?>_NombreTitular"><?php echo (strval($usuarios->NombreTitular->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $usuarios->NombreTitular->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($usuarios->NombreTitular->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $usuarios_list->RowIndex ?>_NombreTitular',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="usuarios" data-field="x_NombreTitular" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $usuarios->NombreTitular->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $usuarios_list->RowIndex ?>_NombreTitular" id="x<?php echo $usuarios_list->RowIndex ?>_NombreTitular" value="<?php echo $usuarios->NombreTitular->CurrentValue ?>"<?php echo $usuarios->NombreTitular->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $usuarios_list->RowIndex ?>_NombreTitular" id="s_x<?php echo $usuarios_list->RowIndex ?>_NombreTitular" value="<?php echo $usuarios->NombreTitular->LookupFilterQuery() ?>">
<input type="hidden" name="ln_x<?php echo $usuarios_list->RowIndex ?>_NombreTitular" id="ln_x<?php echo $usuarios_list->RowIndex ?>_NombreTitular" value="x<?php echo $usuarios_list->RowIndex ?>_Dni,x<?php echo $usuarios_list->RowIndex ?>_Curso,x<?php echo $usuarios_list->RowIndex ?>_Turno,x<?php echo $usuarios_list->RowIndex ?>_Division">
</span>
<?php } ?>
<?php if ($usuarios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_NombreTitular" class="usuarios_NombreTitular">
<span<?php echo $usuarios->NombreTitular->ViewAttributes() ?>>
<?php echo $usuarios->NombreTitular->ListViewValue() ?></span>
</span>
<?php } ?>
<a id="<?php echo $usuarios_list->PageObjName . "_row_" . $usuarios_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($usuarios->Dni->Visible) { // Dni ?>
		<td data-name="Dni"<?php echo $usuarios->Dni->CellAttributes() ?>>
<?php if ($usuarios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Dni" class="form-group usuarios_Dni">
<input type="text" data-table="usuarios" data-field="x_Dni" name="x<?php echo $usuarios_list->RowIndex ?>_Dni" id="x<?php echo $usuarios_list->RowIndex ?>_Dni" size="30" placeholder="<?php echo ew_HtmlEncode($usuarios->Dni->getPlaceHolder()) ?>" value="<?php echo $usuarios->Dni->EditValue ?>"<?php echo $usuarios->Dni->EditAttributes() ?>>
</span>
<input type="hidden" data-table="usuarios" data-field="x_Dni" name="o<?php echo $usuarios_list->RowIndex ?>_Dni" id="o<?php echo $usuarios_list->RowIndex ?>_Dni" value="<?php echo ew_HtmlEncode($usuarios->Dni->OldValue) ?>">
<?php } ?>
<?php if ($usuarios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Dni" class="form-group usuarios_Dni">
<input type="text" data-table="usuarios" data-field="x_Dni" name="x<?php echo $usuarios_list->RowIndex ?>_Dni" id="x<?php echo $usuarios_list->RowIndex ?>_Dni" size="30" placeholder="<?php echo ew_HtmlEncode($usuarios->Dni->getPlaceHolder()) ?>" value="<?php echo $usuarios->Dni->EditValue ?>"<?php echo $usuarios->Dni->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($usuarios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Dni" class="usuarios_Dni">
<span<?php echo $usuarios->Dni->ViewAttributes() ?>>
<?php echo $usuarios->Dni->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($usuarios->Nombre->Visible) { // Nombre ?>
		<td data-name="Nombre"<?php echo $usuarios->Nombre->CellAttributes() ?>>
<?php if ($usuarios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if (!$Security->IsAdmin() && $Security->IsLoggedIn() && !$usuarios->UserIDAllow($usuarios->CurrentAction)) { // Non system admin ?>
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Nombre" class="form-group usuarios_Nombre">
<span<?php echo $usuarios->Nombre->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $usuarios->Nombre->EditValue ?></p></span>
</span>
<input type="hidden" data-table="usuarios" data-field="x_Nombre" name="x<?php echo $usuarios_list->RowIndex ?>_Nombre" id="x<?php echo $usuarios_list->RowIndex ?>_Nombre" value="<?php echo ew_HtmlEncode($usuarios->Nombre->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Nombre" class="form-group usuarios_Nombre">
<input type="text" data-table="usuarios" data-field="x_Nombre" name="x<?php echo $usuarios_list->RowIndex ?>_Nombre" id="x<?php echo $usuarios_list->RowIndex ?>_Nombre" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($usuarios->Nombre->getPlaceHolder()) ?>" value="<?php echo $usuarios->Nombre->EditValue ?>"<?php echo $usuarios->Nombre->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="usuarios" data-field="x_Nombre" name="o<?php echo $usuarios_list->RowIndex ?>_Nombre" id="o<?php echo $usuarios_list->RowIndex ?>_Nombre" value="<?php echo ew_HtmlEncode($usuarios->Nombre->OldValue) ?>">
<?php } ?>
<?php if ($usuarios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Nombre" class="form-group usuarios_Nombre">
<span<?php echo $usuarios->Nombre->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $usuarios->Nombre->EditValue ?></p></span>
</span>
<input type="hidden" data-table="usuarios" data-field="x_Nombre" name="x<?php echo $usuarios_list->RowIndex ?>_Nombre" id="x<?php echo $usuarios_list->RowIndex ?>_Nombre" value="<?php echo ew_HtmlEncode($usuarios->Nombre->CurrentValue) ?>">
<?php } ?>
<?php if ($usuarios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Nombre" class="usuarios_Nombre">
<span<?php echo $usuarios->Nombre->ViewAttributes() ?>>
<?php echo $usuarios->Nombre->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($usuarios->Password->Visible) { // Password ?>
		<td data-name="Password"<?php echo $usuarios->Password->CellAttributes() ?>>
<?php if ($usuarios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Password" class="form-group usuarios_Password">
<div class="input-group" id="ig<?php echo $usuarios_list->RowIndex ?>_Password">
<input type="text" data-password-strength="pst<?php echo $usuarios_list->RowIndex ?>_Password" data-password-generated="pgt<?php echo $usuarios_list->RowIndex ?>_Password" data-table="usuarios" data-field="x_Password" name="x<?php echo $usuarios_list->RowIndex ?>_Password" id="x<?php echo $usuarios_list->RowIndex ?>_Password" value="<?php echo $usuarios->Password->EditValue ?>" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($usuarios->Password->getPlaceHolder()) ?>"<?php echo $usuarios->Password->EditAttributes() ?>>
<span class="input-group-btn">
	<button type="button" class="btn btn-default ewPasswordGenerator" title="<?php echo ew_HtmlTitle($Language->Phrase("GeneratePassword")) ?>" data-password-field="x<?php echo $usuarios_list->RowIndex ?>_Password" data-password-confirm="c<?php echo $usuarios_list->RowIndex ?>_Password" data-password-strength="pst<?php echo $usuarios_list->RowIndex ?>_Password" data-password-generated="pgt<?php echo $usuarios_list->RowIndex ?>_Password"><?php echo $Language->Phrase("GeneratePassword") ?></button>
</span>
</div>
<span class="help-block" id="pgt<?php echo $usuarios_list->RowIndex ?>_Password" style="display: none;"></span>
<div class="progress ewPasswordStrengthBar" id="pst<?php echo $usuarios_list->RowIndex ?>_Password" style="display: none;">
	<div class="progress-bar" role="progressbar"></div>
</div>
</span>
<input type="hidden" data-table="usuarios" data-field="x_Password" name="o<?php echo $usuarios_list->RowIndex ?>_Password" id="o<?php echo $usuarios_list->RowIndex ?>_Password" value="<?php echo ew_HtmlEncode($usuarios->Password->OldValue) ?>">
<?php } ?>
<?php if ($usuarios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Password" class="form-group usuarios_Password">
<div class="input-group" id="ig<?php echo $usuarios_list->RowIndex ?>_Password">
<input type="text" data-password-strength="pst<?php echo $usuarios_list->RowIndex ?>_Password" data-password-generated="pgt<?php echo $usuarios_list->RowIndex ?>_Password" data-table="usuarios" data-field="x_Password" name="x<?php echo $usuarios_list->RowIndex ?>_Password" id="x<?php echo $usuarios_list->RowIndex ?>_Password" value="<?php echo $usuarios->Password->EditValue ?>" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($usuarios->Password->getPlaceHolder()) ?>"<?php echo $usuarios->Password->EditAttributes() ?>>
<span class="input-group-btn">
	<button type="button" class="btn btn-default ewPasswordGenerator" title="<?php echo ew_HtmlTitle($Language->Phrase("GeneratePassword")) ?>" data-password-field="x<?php echo $usuarios_list->RowIndex ?>_Password" data-password-confirm="c<?php echo $usuarios_list->RowIndex ?>_Password" data-password-strength="pst<?php echo $usuarios_list->RowIndex ?>_Password" data-password-generated="pgt<?php echo $usuarios_list->RowIndex ?>_Password"><?php echo $Language->Phrase("GeneratePassword") ?></button>
</span>
</div>
<span class="help-block" id="pgt<?php echo $usuarios_list->RowIndex ?>_Password" style="display: none;"></span>
<div class="progress ewPasswordStrengthBar" id="pst<?php echo $usuarios_list->RowIndex ?>_Password" style="display: none;">
	<div class="progress-bar" role="progressbar"></div>
</div>
</span>
<?php } ?>
<?php if ($usuarios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Password" class="usuarios_Password">
<span<?php echo $usuarios->Password->ViewAttributes() ?>>
<?php echo $usuarios->Password->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($usuarios->Nivel_Usuario->Visible) { // Nivel_Usuario ?>
		<td data-name="Nivel_Usuario"<?php echo $usuarios->Nivel_Usuario->CellAttributes() ?>>
<?php if ($usuarios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if (!$Security->IsAdmin() && $Security->IsLoggedIn()) { // Non system admin ?>
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Nivel_Usuario" class="form-group usuarios_Nivel_Usuario">
<p class="form-control-static"><?php echo $usuarios->Nivel_Usuario->EditValue ?></p>
</span>
<?php } else { ?>
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Nivel_Usuario" class="form-group usuarios_Nivel_Usuario">
<select data-table="usuarios" data-field="x_Nivel_Usuario" data-value-separator="<?php echo $usuarios->Nivel_Usuario->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $usuarios_list->RowIndex ?>_Nivel_Usuario" name="x<?php echo $usuarios_list->RowIndex ?>_Nivel_Usuario"<?php echo $usuarios->Nivel_Usuario->EditAttributes() ?>>
<?php echo $usuarios->Nivel_Usuario->SelectOptionListHtml("x<?php echo $usuarios_list->RowIndex ?>_Nivel_Usuario") ?>
</select>
<input type="hidden" name="s_x<?php echo $usuarios_list->RowIndex ?>_Nivel_Usuario" id="s_x<?php echo $usuarios_list->RowIndex ?>_Nivel_Usuario" value="<?php echo $usuarios->Nivel_Usuario->LookupFilterQuery() ?>">
</span>
<?php } ?>
<input type="hidden" data-table="usuarios" data-field="x_Nivel_Usuario" name="o<?php echo $usuarios_list->RowIndex ?>_Nivel_Usuario" id="o<?php echo $usuarios_list->RowIndex ?>_Nivel_Usuario" value="<?php echo ew_HtmlEncode($usuarios->Nivel_Usuario->OldValue) ?>">
<?php } ?>
<?php if ($usuarios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if (!$Security->IsAdmin() && $Security->IsLoggedIn()) { // Non system admin ?>
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Nivel_Usuario" class="form-group usuarios_Nivel_Usuario">
<p class="form-control-static"><?php echo $usuarios->Nivel_Usuario->EditValue ?></p>
</span>
<?php } else { ?>
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Nivel_Usuario" class="form-group usuarios_Nivel_Usuario">
<select data-table="usuarios" data-field="x_Nivel_Usuario" data-value-separator="<?php echo $usuarios->Nivel_Usuario->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $usuarios_list->RowIndex ?>_Nivel_Usuario" name="x<?php echo $usuarios_list->RowIndex ?>_Nivel_Usuario"<?php echo $usuarios->Nivel_Usuario->EditAttributes() ?>>
<?php echo $usuarios->Nivel_Usuario->SelectOptionListHtml("x<?php echo $usuarios_list->RowIndex ?>_Nivel_Usuario") ?>
</select>
<input type="hidden" name="s_x<?php echo $usuarios_list->RowIndex ?>_Nivel_Usuario" id="s_x<?php echo $usuarios_list->RowIndex ?>_Nivel_Usuario" value="<?php echo $usuarios->Nivel_Usuario->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } ?>
<?php if ($usuarios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Nivel_Usuario" class="usuarios_Nivel_Usuario">
<span<?php echo $usuarios->Nivel_Usuario->ViewAttributes() ?>>
<?php echo $usuarios->Nivel_Usuario->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($usuarios->Curso->Visible) { // Curso ?>
		<td data-name="Curso"<?php echo $usuarios->Curso->CellAttributes() ?>>
<?php if ($usuarios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Curso" class="form-group usuarios_Curso">
<select data-table="usuarios" data-field="x_Curso" data-value-separator="<?php echo $usuarios->Curso->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $usuarios_list->RowIndex ?>_Curso" name="x<?php echo $usuarios_list->RowIndex ?>_Curso"<?php echo $usuarios->Curso->EditAttributes() ?>>
<?php echo $usuarios->Curso->SelectOptionListHtml("x<?php echo $usuarios_list->RowIndex ?>_Curso") ?>
</select>
<input type="hidden" name="s_x<?php echo $usuarios_list->RowIndex ?>_Curso" id="s_x<?php echo $usuarios_list->RowIndex ?>_Curso" value="<?php echo $usuarios->Curso->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="usuarios" data-field="x_Curso" name="o<?php echo $usuarios_list->RowIndex ?>_Curso" id="o<?php echo $usuarios_list->RowIndex ?>_Curso" value="<?php echo ew_HtmlEncode($usuarios->Curso->OldValue) ?>">
<?php } ?>
<?php if ($usuarios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Curso" class="form-group usuarios_Curso">
<select data-table="usuarios" data-field="x_Curso" data-value-separator="<?php echo $usuarios->Curso->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $usuarios_list->RowIndex ?>_Curso" name="x<?php echo $usuarios_list->RowIndex ?>_Curso"<?php echo $usuarios->Curso->EditAttributes() ?>>
<?php echo $usuarios->Curso->SelectOptionListHtml("x<?php echo $usuarios_list->RowIndex ?>_Curso") ?>
</select>
<input type="hidden" name="s_x<?php echo $usuarios_list->RowIndex ?>_Curso" id="s_x<?php echo $usuarios_list->RowIndex ?>_Curso" value="<?php echo $usuarios->Curso->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($usuarios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Curso" class="usuarios_Curso">
<span<?php echo $usuarios->Curso->ViewAttributes() ?>>
<?php echo $usuarios->Curso->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($usuarios->Turno->Visible) { // Turno ?>
		<td data-name="Turno"<?php echo $usuarios->Turno->CellAttributes() ?>>
<?php if ($usuarios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Turno" class="form-group usuarios_Turno">
<select data-table="usuarios" data-field="x_Turno" data-value-separator="<?php echo $usuarios->Turno->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $usuarios_list->RowIndex ?>_Turno" name="x<?php echo $usuarios_list->RowIndex ?>_Turno"<?php echo $usuarios->Turno->EditAttributes() ?>>
<?php echo $usuarios->Turno->SelectOptionListHtml("x<?php echo $usuarios_list->RowIndex ?>_Turno") ?>
</select>
<input type="hidden" name="s_x<?php echo $usuarios_list->RowIndex ?>_Turno" id="s_x<?php echo $usuarios_list->RowIndex ?>_Turno" value="<?php echo $usuarios->Turno->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="usuarios" data-field="x_Turno" name="o<?php echo $usuarios_list->RowIndex ?>_Turno" id="o<?php echo $usuarios_list->RowIndex ?>_Turno" value="<?php echo ew_HtmlEncode($usuarios->Turno->OldValue) ?>">
<?php } ?>
<?php if ($usuarios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Turno" class="form-group usuarios_Turno">
<select data-table="usuarios" data-field="x_Turno" data-value-separator="<?php echo $usuarios->Turno->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $usuarios_list->RowIndex ?>_Turno" name="x<?php echo $usuarios_list->RowIndex ?>_Turno"<?php echo $usuarios->Turno->EditAttributes() ?>>
<?php echo $usuarios->Turno->SelectOptionListHtml("x<?php echo $usuarios_list->RowIndex ?>_Turno") ?>
</select>
<input type="hidden" name="s_x<?php echo $usuarios_list->RowIndex ?>_Turno" id="s_x<?php echo $usuarios_list->RowIndex ?>_Turno" value="<?php echo $usuarios->Turno->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($usuarios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Turno" class="usuarios_Turno">
<span<?php echo $usuarios->Turno->ViewAttributes() ?>>
<?php echo $usuarios->Turno->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($usuarios->Division->Visible) { // Division ?>
		<td data-name="Division"<?php echo $usuarios->Division->CellAttributes() ?>>
<?php if ($usuarios->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Division" class="form-group usuarios_Division">
<select data-table="usuarios" data-field="x_Division" data-value-separator="<?php echo $usuarios->Division->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $usuarios_list->RowIndex ?>_Division" name="x<?php echo $usuarios_list->RowIndex ?>_Division"<?php echo $usuarios->Division->EditAttributes() ?>>
<?php echo $usuarios->Division->SelectOptionListHtml("x<?php echo $usuarios_list->RowIndex ?>_Division") ?>
</select>
<input type="hidden" name="s_x<?php echo $usuarios_list->RowIndex ?>_Division" id="s_x<?php echo $usuarios_list->RowIndex ?>_Division" value="<?php echo $usuarios->Division->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="usuarios" data-field="x_Division" name="o<?php echo $usuarios_list->RowIndex ?>_Division" id="o<?php echo $usuarios_list->RowIndex ?>_Division" value="<?php echo ew_HtmlEncode($usuarios->Division->OldValue) ?>">
<?php } ?>
<?php if ($usuarios->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Division" class="form-group usuarios_Division">
<select data-table="usuarios" data-field="x_Division" data-value-separator="<?php echo $usuarios->Division->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $usuarios_list->RowIndex ?>_Division" name="x<?php echo $usuarios_list->RowIndex ?>_Division"<?php echo $usuarios->Division->EditAttributes() ?>>
<?php echo $usuarios->Division->SelectOptionListHtml("x<?php echo $usuarios_list->RowIndex ?>_Division") ?>
</select>
<input type="hidden" name="s_x<?php echo $usuarios_list->RowIndex ?>_Division" id="s_x<?php echo $usuarios_list->RowIndex ?>_Division" value="<?php echo $usuarios->Division->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($usuarios->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $usuarios_list->RowCnt ?>_usuarios_Division" class="usuarios_Division">
<span<?php echo $usuarios->Division->ViewAttributes() ?>>
<?php echo $usuarios->Division->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$usuarios_list->ListOptions->Render("body", "right", $usuarios_list->RowCnt);
?>
	</tr>
<?php if ($usuarios->RowType == EW_ROWTYPE_ADD || $usuarios->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fusuarioslist.UpdateOpts(<?php echo $usuarios_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($usuarios->CurrentAction <> "gridadd")
		if (!$usuarios_list->Recordset->EOF) $usuarios_list->Recordset->MoveNext();
}
?>
<?php
	if ($usuarios->CurrentAction == "gridadd" || $usuarios->CurrentAction == "gridedit") {
		$usuarios_list->RowIndex = '$rowindex$';
		$usuarios_list->LoadDefaultValues();

		// Set row properties
		$usuarios->ResetAttrs();
		$usuarios->RowAttrs = array_merge($usuarios->RowAttrs, array('data-rowindex'=>$usuarios_list->RowIndex, 'id'=>'r0_usuarios', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($usuarios->RowAttrs["class"], "ewTemplate");
		$usuarios->RowType = EW_ROWTYPE_ADD;

		// Render row
		$usuarios_list->RenderRow();

		// Render list options
		$usuarios_list->RenderListOptions();
		$usuarios_list->StartRowCnt = 0;
?>
	<tr<?php echo $usuarios->RowAttributes() ?>>
<?php

// Render list options (body, left)
$usuarios_list->ListOptions->Render("body", "left", $usuarios_list->RowIndex);
?>
	<?php if ($usuarios->NombreTitular->Visible) { // NombreTitular ?>
		<td data-name="NombreTitular">
<span id="el$rowindex$_usuarios_NombreTitular" class="form-group usuarios_NombreTitular">
<?php $usuarios->NombreTitular->EditAttrs["onchange"] = "ew_AutoFill(this); " . @$usuarios->NombreTitular->EditAttrs["onchange"]; ?>
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $usuarios_list->RowIndex ?>_NombreTitular"><?php echo (strval($usuarios->NombreTitular->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $usuarios->NombreTitular->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($usuarios->NombreTitular->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $usuarios_list->RowIndex ?>_NombreTitular',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="usuarios" data-field="x_NombreTitular" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $usuarios->NombreTitular->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $usuarios_list->RowIndex ?>_NombreTitular" id="x<?php echo $usuarios_list->RowIndex ?>_NombreTitular" value="<?php echo $usuarios->NombreTitular->CurrentValue ?>"<?php echo $usuarios->NombreTitular->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $usuarios_list->RowIndex ?>_NombreTitular" id="s_x<?php echo $usuarios_list->RowIndex ?>_NombreTitular" value="<?php echo $usuarios->NombreTitular->LookupFilterQuery() ?>">
<input type="hidden" name="ln_x<?php echo $usuarios_list->RowIndex ?>_NombreTitular" id="ln_x<?php echo $usuarios_list->RowIndex ?>_NombreTitular" value="x<?php echo $usuarios_list->RowIndex ?>_Dni,x<?php echo $usuarios_list->RowIndex ?>_Curso,x<?php echo $usuarios_list->RowIndex ?>_Turno,x<?php echo $usuarios_list->RowIndex ?>_Division">
</span>
<input type="hidden" data-table="usuarios" data-field="x_NombreTitular" name="o<?php echo $usuarios_list->RowIndex ?>_NombreTitular" id="o<?php echo $usuarios_list->RowIndex ?>_NombreTitular" value="<?php echo ew_HtmlEncode($usuarios->NombreTitular->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($usuarios->Dni->Visible) { // Dni ?>
		<td data-name="Dni">
<span id="el$rowindex$_usuarios_Dni" class="form-group usuarios_Dni">
<input type="text" data-table="usuarios" data-field="x_Dni" name="x<?php echo $usuarios_list->RowIndex ?>_Dni" id="x<?php echo $usuarios_list->RowIndex ?>_Dni" size="30" placeholder="<?php echo ew_HtmlEncode($usuarios->Dni->getPlaceHolder()) ?>" value="<?php echo $usuarios->Dni->EditValue ?>"<?php echo $usuarios->Dni->EditAttributes() ?>>
</span>
<input type="hidden" data-table="usuarios" data-field="x_Dni" name="o<?php echo $usuarios_list->RowIndex ?>_Dni" id="o<?php echo $usuarios_list->RowIndex ?>_Dni" value="<?php echo ew_HtmlEncode($usuarios->Dni->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($usuarios->Nombre->Visible) { // Nombre ?>
		<td data-name="Nombre">
<?php if (!$Security->IsAdmin() && $Security->IsLoggedIn() && !$usuarios->UserIDAllow($usuarios->CurrentAction)) { // Non system admin ?>
<span id="el$rowindex$_usuarios_Nombre" class="form-group usuarios_Nombre">
<span<?php echo $usuarios->Nombre->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $usuarios->Nombre->EditValue ?></p></span>
</span>
<input type="hidden" data-table="usuarios" data-field="x_Nombre" name="x<?php echo $usuarios_list->RowIndex ?>_Nombre" id="x<?php echo $usuarios_list->RowIndex ?>_Nombre" value="<?php echo ew_HtmlEncode($usuarios->Nombre->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_usuarios_Nombre" class="form-group usuarios_Nombre">
<input type="text" data-table="usuarios" data-field="x_Nombre" name="x<?php echo $usuarios_list->RowIndex ?>_Nombre" id="x<?php echo $usuarios_list->RowIndex ?>_Nombre" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($usuarios->Nombre->getPlaceHolder()) ?>" value="<?php echo $usuarios->Nombre->EditValue ?>"<?php echo $usuarios->Nombre->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="usuarios" data-field="x_Nombre" name="o<?php echo $usuarios_list->RowIndex ?>_Nombre" id="o<?php echo $usuarios_list->RowIndex ?>_Nombre" value="<?php echo ew_HtmlEncode($usuarios->Nombre->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($usuarios->Password->Visible) { // Password ?>
		<td data-name="Password">
<span id="el$rowindex$_usuarios_Password" class="form-group usuarios_Password">
<div class="input-group" id="ig<?php echo $usuarios_list->RowIndex ?>_Password">
<input type="text" data-password-strength="pst<?php echo $usuarios_list->RowIndex ?>_Password" data-password-generated="pgt<?php echo $usuarios_list->RowIndex ?>_Password" data-table="usuarios" data-field="x_Password" name="x<?php echo $usuarios_list->RowIndex ?>_Password" id="x<?php echo $usuarios_list->RowIndex ?>_Password" value="<?php echo $usuarios->Password->EditValue ?>" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($usuarios->Password->getPlaceHolder()) ?>"<?php echo $usuarios->Password->EditAttributes() ?>>
<span class="input-group-btn">
	<button type="button" class="btn btn-default ewPasswordGenerator" title="<?php echo ew_HtmlTitle($Language->Phrase("GeneratePassword")) ?>" data-password-field="x<?php echo $usuarios_list->RowIndex ?>_Password" data-password-confirm="c<?php echo $usuarios_list->RowIndex ?>_Password" data-password-strength="pst<?php echo $usuarios_list->RowIndex ?>_Password" data-password-generated="pgt<?php echo $usuarios_list->RowIndex ?>_Password"><?php echo $Language->Phrase("GeneratePassword") ?></button>
</span>
</div>
<span class="help-block" id="pgt<?php echo $usuarios_list->RowIndex ?>_Password" style="display: none;"></span>
<div class="progress ewPasswordStrengthBar" id="pst<?php echo $usuarios_list->RowIndex ?>_Password" style="display: none;">
	<div class="progress-bar" role="progressbar"></div>
</div>
</span>
<input type="hidden" data-table="usuarios" data-field="x_Password" name="o<?php echo $usuarios_list->RowIndex ?>_Password" id="o<?php echo $usuarios_list->RowIndex ?>_Password" value="<?php echo ew_HtmlEncode($usuarios->Password->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($usuarios->Nivel_Usuario->Visible) { // Nivel_Usuario ?>
		<td data-name="Nivel_Usuario">
<?php if (!$Security->IsAdmin() && $Security->IsLoggedIn()) { // Non system admin ?>
<span id="el$rowindex$_usuarios_Nivel_Usuario" class="form-group usuarios_Nivel_Usuario">
<p class="form-control-static"><?php echo $usuarios->Nivel_Usuario->EditValue ?></p>
</span>
<?php } else { ?>
<span id="el$rowindex$_usuarios_Nivel_Usuario" class="form-group usuarios_Nivel_Usuario">
<select data-table="usuarios" data-field="x_Nivel_Usuario" data-value-separator="<?php echo $usuarios->Nivel_Usuario->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $usuarios_list->RowIndex ?>_Nivel_Usuario" name="x<?php echo $usuarios_list->RowIndex ?>_Nivel_Usuario"<?php echo $usuarios->Nivel_Usuario->EditAttributes() ?>>
<?php echo $usuarios->Nivel_Usuario->SelectOptionListHtml("x<?php echo $usuarios_list->RowIndex ?>_Nivel_Usuario") ?>
</select>
<input type="hidden" name="s_x<?php echo $usuarios_list->RowIndex ?>_Nivel_Usuario" id="s_x<?php echo $usuarios_list->RowIndex ?>_Nivel_Usuario" value="<?php echo $usuarios->Nivel_Usuario->LookupFilterQuery() ?>">
</span>
<?php } ?>
<input type="hidden" data-table="usuarios" data-field="x_Nivel_Usuario" name="o<?php echo $usuarios_list->RowIndex ?>_Nivel_Usuario" id="o<?php echo $usuarios_list->RowIndex ?>_Nivel_Usuario" value="<?php echo ew_HtmlEncode($usuarios->Nivel_Usuario->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($usuarios->Curso->Visible) { // Curso ?>
		<td data-name="Curso">
<span id="el$rowindex$_usuarios_Curso" class="form-group usuarios_Curso">
<select data-table="usuarios" data-field="x_Curso" data-value-separator="<?php echo $usuarios->Curso->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $usuarios_list->RowIndex ?>_Curso" name="x<?php echo $usuarios_list->RowIndex ?>_Curso"<?php echo $usuarios->Curso->EditAttributes() ?>>
<?php echo $usuarios->Curso->SelectOptionListHtml("x<?php echo $usuarios_list->RowIndex ?>_Curso") ?>
</select>
<input type="hidden" name="s_x<?php echo $usuarios_list->RowIndex ?>_Curso" id="s_x<?php echo $usuarios_list->RowIndex ?>_Curso" value="<?php echo $usuarios->Curso->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="usuarios" data-field="x_Curso" name="o<?php echo $usuarios_list->RowIndex ?>_Curso" id="o<?php echo $usuarios_list->RowIndex ?>_Curso" value="<?php echo ew_HtmlEncode($usuarios->Curso->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($usuarios->Turno->Visible) { // Turno ?>
		<td data-name="Turno">
<span id="el$rowindex$_usuarios_Turno" class="form-group usuarios_Turno">
<select data-table="usuarios" data-field="x_Turno" data-value-separator="<?php echo $usuarios->Turno->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $usuarios_list->RowIndex ?>_Turno" name="x<?php echo $usuarios_list->RowIndex ?>_Turno"<?php echo $usuarios->Turno->EditAttributes() ?>>
<?php echo $usuarios->Turno->SelectOptionListHtml("x<?php echo $usuarios_list->RowIndex ?>_Turno") ?>
</select>
<input type="hidden" name="s_x<?php echo $usuarios_list->RowIndex ?>_Turno" id="s_x<?php echo $usuarios_list->RowIndex ?>_Turno" value="<?php echo $usuarios->Turno->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="usuarios" data-field="x_Turno" name="o<?php echo $usuarios_list->RowIndex ?>_Turno" id="o<?php echo $usuarios_list->RowIndex ?>_Turno" value="<?php echo ew_HtmlEncode($usuarios->Turno->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($usuarios->Division->Visible) { // Division ?>
		<td data-name="Division">
<span id="el$rowindex$_usuarios_Division" class="form-group usuarios_Division">
<select data-table="usuarios" data-field="x_Division" data-value-separator="<?php echo $usuarios->Division->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $usuarios_list->RowIndex ?>_Division" name="x<?php echo $usuarios_list->RowIndex ?>_Division"<?php echo $usuarios->Division->EditAttributes() ?>>
<?php echo $usuarios->Division->SelectOptionListHtml("x<?php echo $usuarios_list->RowIndex ?>_Division") ?>
</select>
<input type="hidden" name="s_x<?php echo $usuarios_list->RowIndex ?>_Division" id="s_x<?php echo $usuarios_list->RowIndex ?>_Division" value="<?php echo $usuarios->Division->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="usuarios" data-field="x_Division" name="o<?php echo $usuarios_list->RowIndex ?>_Division" id="o<?php echo $usuarios_list->RowIndex ?>_Division" value="<?php echo ew_HtmlEncode($usuarios->Division->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$usuarios_list->ListOptions->Render("body", "right", $usuarios_list->RowCnt);
?>
<script type="text/javascript">
fusuarioslist.UpdateOpts(<?php echo $usuarios_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($usuarios->CurrentAction == "add" || $usuarios->CurrentAction == "copy") { ?>
<input type="hidden" name="<?php echo $usuarios_list->FormKeyCountName ?>" id="<?php echo $usuarios_list->FormKeyCountName ?>" value="<?php echo $usuarios_list->KeyCount ?>">
<?php } ?>
<?php if ($usuarios->CurrentAction == "gridadd") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $usuarios_list->FormKeyCountName ?>" id="<?php echo $usuarios_list->FormKeyCountName ?>" value="<?php echo $usuarios_list->KeyCount ?>">
<?php echo $usuarios_list->MultiSelectKey ?>
<?php } ?>
<?php if ($usuarios->CurrentAction == "edit") { ?>
<input type="hidden" name="<?php echo $usuarios_list->FormKeyCountName ?>" id="<?php echo $usuarios_list->FormKeyCountName ?>" value="<?php echo $usuarios_list->KeyCount ?>">
<?php } ?>
<?php if ($usuarios->CurrentAction == "gridedit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $usuarios_list->FormKeyCountName ?>" id="<?php echo $usuarios_list->FormKeyCountName ?>" value="<?php echo $usuarios_list->KeyCount ?>">
<?php echo $usuarios_list->MultiSelectKey ?>
<?php } ?>
<?php if ($usuarios->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($usuarios_list->Recordset)
	$usuarios_list->Recordset->Close();
?>
<?php if ($usuarios->Export == "") { ?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($usuarios->CurrentAction <> "gridadd" && $usuarios->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($usuarios_list->Pager)) $usuarios_list->Pager = new cPrevNextPager($usuarios_list->StartRec, $usuarios_list->DisplayRecs, $usuarios_list->TotalRecs) ?>
<?php if ($usuarios_list->Pager->RecordCount > 0 && $usuarios_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($usuarios_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $usuarios_list->PageUrl() ?>start=<?php echo $usuarios_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($usuarios_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $usuarios_list->PageUrl() ?>start=<?php echo $usuarios_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $usuarios_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($usuarios_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $usuarios_list->PageUrl() ?>start=<?php echo $usuarios_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($usuarios_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $usuarios_list->PageUrl() ?>start=<?php echo $usuarios_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $usuarios_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $usuarios_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $usuarios_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $usuarios_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($usuarios_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($usuarios_list->TotalRecs == 0 && $usuarios->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($usuarios_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($usuarios->Export == "") { ?>
<script type="text/javascript">
fusuarioslistsrch.FilterList = <?php echo $usuarios_list->GetFilterList() ?>;
fusuarioslistsrch.Init();
fusuarioslist.Init();
</script>
<?php } ?>
<?php
$usuarios_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($usuarios->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$usuarios_list->Page_Terminate();
?>