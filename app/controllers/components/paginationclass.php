<?php
class PaginationclassComponent extends Object
{
		public $intTotalRecords;
		public  $intPageSize;
		public  $intLinkDisplay;
		public  $intTotalPages;
		public  $strQueryString;
		public  $strFirstPageFormat;
		public  $strLastPageFormat;
		public  $strNextPageFormat;
		public  $strPreviousPageFormat;
		public  $strPageLink;
		public  $strDots;
		private $arrQueryString;
		private $inactiveCSS;
		private $activeCSS;
		public  $strType;
		public  $strFunctionName;
		private $strVariable;
		private $strFunction;
		public  $arrVariable = array();
		//public $pageStart;
		//public $pageEnd;
		public function __construct(){
			$this->intTotalRecords 		= 0;
			$this->intPageSize 			= 0;
			$this->intLinkDisplay 		= 5;
			$this->intTotalPages 		= 0;
			$this->strQueryString 		= "";
			$this->strFirstPageFormat 	= "";
			$this->strLastPageFormat 	= "";
			$this->strNextPageFormat 	= "next >>";
			$this->strPreviousPageFormat= "<< previous";
			$this->strPageLink 			= "";
			$this->strDots				= "";
			$this->strType 				= "";
			$this->strFunctionName		= "";
			$this->inactiveCSS 			= "blacktext1";
			$this->activeCSS   			= "FBblue";
			//$this->pageStart=0;
			//$this->pageEnd=0;
		}
		function startup( &$controller ) {
			$this->controller = &$controller;
		}
		public function setTotalRecords($NoOfRecords){
			$this->intTotalRecords = $NoOfRecords;
		}
		public function getTotalRecords(){
			//echo $this->intTotalRecords = 7;
			return $this->intTotalRecords;
		}
		public function getTotalPages(){
			//$this->intPageSize = 10;
			// echo $this->intTotalRecords;
			// echo $this->intPageSize;
			if (($this->intTotalRecords%$this->intPageSize)==0)
				$this->intTotalPages = $this->intTotalRecords/$this->intPageSize;
			else
				$this->intTotalPages = ceil($this->intTotalRecords/$this->intPageSize);
			return $this->intTotalPages;
		}
		public function setPageLink($hrefLink){
			$this->strPageLink = $hrefLink;
		}
		public function getPageLink(){		
			return $this->strPageLink;
		}
		public function showPagination($intPageNumber){
			$strPage ="";
			$href="";
			$dots="..";
			$strVar      = "";
			for($j=0;$j<count($this->arrVariables);$j++){
				$strVar .= '"'.$this->arrVariables[$j].'",';
			}
			$this->strVariables = rtrim($strVar,",");
			if($intPageNumber<=0 || $intPageNumber>$this->intTotalPages)
				$intPageNumber=1;
				$intStartBlock	= floor(($intPageNumber -0.01)/$this->intLinkDisplay);
				$intStartPage	= ($intStartBlock * $this->intLinkDisplay) +1;
				$intEndPage		= $intStartPage+$this->intLinkDisplay-1;
			if ($intEndPage>=$this->intTotalPages){
				$intEndPage		= $this->intTotalPages;
			}
			//print $intEndPage;
			$strPage.="<div id='paging'>";
			//$strPage.="<tr>";
			//$strPage.= $this->getFirst($strPage,$intPageNumber);
			$strPage.= $this->getPrevious($strPage,$intPageNumber);
			for($i=$intStartPage;$i<=$intEndPage;$i++){
				$strPage.="<span style='margin-left:3px; margin-right: 3px;'>";
				$strPage.= $this->getFormatedLink($intPageNumber,$i);
				$strPage.="</span>";
			}
			//if($this->strType!="ajax")
				//$strPage.= $this->getDots($strPage,$intEndPage);
			$strPage.= $this->getNext($strPage,$intPageNumber);
			//$strPage.= $this->getLast($strPage,$intPageNumber);
			//$strPage.="</tr>";
			$strPage.="</div>";
			//echo $strFormettedLink = $this->getFormatedLink($intPageNumber,$i);
			return $strPage;
		}
		private function getFormatedLink($intCurrentPageNo,$intGeneratedPageNo){
			//$strFunction = "";
			$this->strFunction = $this->strFunctionName."(".$this->strVariables.",".$intGeneratedPageNo.")";
			$href="";
			$strQueryString= "_pn=".$intGeneratedPageNo."&".$this->strQueryString;
			if($intGeneratedPageNo <> $intCurrentPageNo){	
				if($this->strType=="ajax" || $this->strType=="script")//class='".$this->activeCSS."'
					$href = "<a class='".$this->activeCSS."' onClick='".$this->strFunction."'>".$intGeneratedPageNo."</a>";
				else
					 $href = "<a  href='".$this->strPageLink.$this->getFormatedQueryString($intGeneratedPageNo)."' class='".$this->activeCSS."'>".$intGeneratedPageNo."</a>";
			}else{
				$href = "<span class='".$this->inactiveCSS."'>".$intGeneratedPageNo."</span>";
			}	
			return $href;
		}
		private function getFormatedQueryString($intPageNo){
			$arrURL 		= parse_url($_SERVER['REQUEST_URI']);
			$strPagePath	=$arrURL['path'];
			$strQueryString	=$arrURL['query'];
			parse_str($strQueryString, $arrQueryString);
			$strQueryString="";
			$arrQueryString['_pn']	=$intPageNo;
			$strQueryString	= "?".http_build_query($arrQueryString);
			return $strQueryString;
		}
		public function getPrevious($strPage,$intPageNumber){
			$intGeneratedPageNo = $intPageNumber-1;
			$this->strFunction = $this->strFunctionName."(".$this->strVariables.",".$intGeneratedPageNo.")";
			$strPage="<td>";
			if ($intPageNumber > 1 ){
				if($this->strType=="ajax" || $this->strType=="script") 
					$strPage.="<a  class='".$this->activeCSS."' onClick='".$this->strFunction."'>".$this->strPreviousPageFormat."</a>";
				else	
					$strPage.="<a href='".$this->strPageLink.$this->getFormatedQueryString($intPageNumber-1)."' class='".$this->activeCSS."'>".$this->strPreviousPageFormat."</a>";
			}else{
				//$strPage.= "<span class='".$this->inactiveCSS."'>".$this->strPreviousPageFormat."</span>";
			}	
			$strPage.="</td>";
			return $strPage;
		}
		public function getNext($strPage,$intPageNumber){
			$intGeneratedPageNo = $intPageNumber+1;
			$this->strFunction = $this->strFunctionName."(".$this->strVariables.",".$intGeneratedPageNo.")";
			$strPage="<td>";
			//echo $intPageNumber.' <> '.$this->intTotalPages;
			if ($intPageNumber <> $this->intTotalPages && $this->intTotalPages!=0){
				if($this->strType=="ajax" || $this->strType=="script")
					$strPage.="<a  class='".$this->activeCSS."' onClick='".$this->strFunction."'>".$this->strNextPageFormat."</a>";
				else
					$strPage.="<a href='".$this->strPageLink.$this->getFormatedQueryString($intPageNumber+1)."' class='".$this->activeCSS."'>".$this->strNextPageFormat."</a>";
			}else{
				//$strPage.= "<span class='".$this->inactiveCSS."'>".$this->strNextPageFormat."</span>";
			}	
			$strPage.="</td>";
			return $strPage;
		}
		public function getFirst($strPage,$intPageNumber){
			$intGeneratedPageNo = 1;
			$this->strFunction = $this->strFunctionName."(".$this->strVariables.",".$intGeneratedPageNo.")";
			$strPage="<td>";
			if (($this->intTotalPages >0) && $intPageNumber > 1){
				if($this->strType=="ajax" || $this->strType=="script")
					$strPage.="<a href='#' class='".$this->activeCSS."' onClick='".$this->strFunction."'>".$this->strFirstPageFormat."</a>";
				else
					$strPage.="<a href='".$this->strPageLink.$this->getFormatedQueryString(1)."' class='".$this->activeCSS."'>".$this->strFirstPageFormat."</a>";
			}else{
				$strPage.= "<span class='".$this->inactiveCSS."'>".$this->strFirstPageFormat."</span>";
			}	
			$strPage.="</td>";
			return $strPage;
		}
		public function getLast($strPage,$intPageNumber){
			$intGeneratedPageNo = $this->intTotalPages;
			$this->strFunction = $this->strFunctionName."(".$this->strVariables.",".$intGeneratedPageNo.")";
			$strPage="<td>";
			if ($intPageNumber<$this->intTotalPages){
				if($this->strType=="ajax" || $this->strType=="script")
					$strPage.="<a href='#' class='".$this->activeCSS."' onClick='".$this->strFunction."'>".$this->strLastPageFormat."</a>";
				else
					$strPage.="<a href='".$this->strPageLink.$this->getFormatedQueryString($this->intTotalPages)."' class='".$this->activeCSS."'>".$this->strLastPageFormat."</a>";
			}else{
				$strPage.= "<span class='".$this->inactiveCSS."'>".$this->strLastPageFormat."</span>";
			}	
			$strPage.="</td>";
			return $strPage;
		}
		public function getDots($strPage,$intEndPage){
			$intGeneratedPageNo = $intEndPage+1;
			$this->strFunction = $this->strFunctionName."(".$this->strVariables.",".$intGeneratedPageNo.")";
			$strPage="<td>";
			if(!($intEndPage>=$this->intTotalPages)){
				if($this->strType=="ajax" || $this->strType=="script")
					$strPage.="<a href='#' class='".$this->activeCSS."' onClick='".$this->strFunction."'>".$this->strDots."</a>";
				else
					$strPage.="<a href='".$this->strPageLink.$this->getFormatedQueryString($intEndPage+1)."'  class='".$this->activeCSS."'>".$this->strDots."</a>";
			}else{
				$strPage.="";
			}	
			$strPage.="</td>";
			return $strPage;
		}
	}
?>