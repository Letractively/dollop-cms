<?php 

       if (!defined('FIRE1_INIT')) { exit("<div style='background-color: #FFAAAA; '> error..1001</div>"); }
       
/************************************************************************


  HTML_table.php 
  version date: dec 2008  
 

@exception

 $PRODUCTS = array( 
    'choc_chip' => array(' Chocolate Chip Cookies', 1.25, 10.00), 
    'oatmeal' => array('Oatmeal Cookies', .99, 8.25), 
    'brwwnies' => array('Fudge Brownies', 1.35, 12.00) 
); 

$tbl = new HTML_Table(null, 'display', 1, 0, 4); 

$tbl->addRow(); 
    $tbl->addCell('Product', 'first', 'header'); 
    $tbl->addCell('Single Item Price', null, 'header'); 
    $tbl->addCell('Price per Dozen', null, 'header'); 
     
    foreach($PRODUCTS as $product) { 
        list($name, $unit_price, $doz_price ) = $product; 
        $tbl->addRow(); 
            $tbl->addCell($name); 
            $tbl->addCell('$' . number_format($unit_price, 2), 'num' ); 
            $tbl->addCell('$' . number_format($doz_price, 2), 'num' ); 
    } 
echo $tbl->display(); 


************************************************************************/



/**
* @package classes
* @author http://www.dyn-web.com/
*/
class html_table { 
     
    private $rows = array(); 
    private $tableStr = ''; 
     
    function __construct($id = NULL, $klass = NULL, $border = 0, $cellspacing = 2, $cellpadding = 0, $attr_ar = array() ) {
    
    if($klass != "admin"){
        $this->tableStr = "\n<table" . ( !empty($id)? " id=\"$id\"": '' ) .  
            ( !empty($klass)? " class=\"$klass\"": '' ) . $this->addAttribs( $attr_ar ) .  
             " border=\"$border\" cellspacing=\"$cellspacing\" cellpadding=\"$cellpadding\">\n";
             
    }else{
     $this->tableStr ="<table width=\"80%\" border=\"0\" align=\"center\" cellpadding=\"8\" cellspacing=\"1\">";
    
    }
    } 
     
     /**
     * Adds Attributes
     * 
     * @param mixed $attr_ar
     */
    private function addAttribs( $attr_ar ) { 
        $str = ''; 
        foreach( $attr_ar as $key=>$val ) { 
            $str .= " $key=\"$val\""; 
        } 
        return $str; 
    } 
     
    public function addRow($klass = NULL, $attr_ar = array() ) { 
        $row = new HTML_TableRow( $klass, $attr_ar ); 
        array_push( $this->rows, $row ); 
    } 
    
     /**
     * Adds Cell to table
     * 
     * @param mixed $data       // value in cell
     * @param mixed $klass      // class
     * @param mixed $type       // data
     * @param mixed $attr_ar    // attribute array
     * 
     */
    public function addCell($data = '', $klass = NULL, $type = 'data', $attr_ar = array() ) {
        $cell = new HTML_TableCell( $data, $klass, $type, $attr_ar ); 
        // add new cell to current row's list of cells 
        $curRow = &$this->rows[ count( $this->rows ) - 1 ]; // copy by reference 
        array_push( $curRow->cells, $cell ); 
    } 
  /**
  * Display table
  *    
  */
    public function display() { 
        foreach( $this->rows as $row ) { 
            $this->tableStr .= !empty($row->klass) ? "  <tr class=\"{$row->klass}\"": "  <tr"; 
            $this->tableStr .= $this->addAttribs( $row->attr_ar ) . ">\n"; 
            $this->tableStr .= $this->getRowCells( $row->cells ); 
            $this->tableStr .= "  </tr>\n"; 
        } 
        $this->tableStr .= "</table>\n"; 
        return $this->tableStr; 
    } 
     
    function getRowCells($cells) { 
        $str = ''; 
        foreach( $cells as $cell ) { 
            $tag = ($cell->type == 'data')? 'td': 'th'; 
            $str .= !empty($cell->klass) ? "    <$tag class=\"$cell->klass\"": "    <$tag"; 
            $str .= $this->addAttribs( $cell->attr_ar ) . ">"; 
            $str .= $cell->data; 
            $str .= "</$tag>\n"; 
        } 
        return $str; 
    } 
     
} 

/**
* Class table row
*/
class html_tableRow { 
    function __construct($klass = NULL, $attr_ar = array()) { 
        $this->klass = $klass; 
        $this->attr_ar = $attr_ar; 
        $this->cells = array(); 
    } 
} 


/**
* Class table cell
*/
class html_tableCell { 
    function __construct( $data, $klass, $type, $attr_ar ) { 
        $this->data = $data; 
        $this->klass = $klass; 
        $this->type = $type; 
        $this->attr_ar = $attr_ar; 
    } 
} 

