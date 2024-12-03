<? php
// Database Configuration
define (’DB_SERVER ’, ’localhost ’) ;
define (’DB_USERNAME ’, ’root ’) ;
define (’DB_PASSWORD ’, ’’) ;
define (’DB_NAME ’, ’student_db ’) ;
/**
* Establishes a secure database connection
*
* @return mysqli Database connection object
* @throws Exception If database connection fails
*/
function connectDatabase () {
$conn = new mysqli ( DB_SERVER , DB_USERNAME , DB_PASSWORD , DB_NAME ) ;
BIT 3rd Semester Assignment 1 Web Technology
if ($conn - > connect_error ) {
error_log (" Database Connection Failed : ". $conn - > connect_error ) ;
throw new Exception (" Database connection error . Please try again later .") ;
}
$conn - > set_charset (" utf8mb4 ") ;
return $conn ;
}
 /**
* Validates and sanitizes student input
*/
function validateStudentData ($conn , $data ) {
$name = trim ( $data [’name ’]) ;
$email = trim ( $data [’email ’]) ;
$age = filter_var ( $data [’age ’] , FILTER_VALIDATE_INT ) ;
if ( empty ( $name ) || strlen ( $name ) > 50)
return false ;
if (! filter_var ( $email ,

FILTER_VALIDATE_EMAIL ) ) return false ;
if ( $age === false || $age < 16 || $age >100) return false ;

return [
’name ’ = > $conn - > real_escape_string ( $name ) ,
’email ’ = > $conn - > real_escape_string ( $email ) ,
’age ’ = > $age
];
 }
/**
* Inserts student data into the database
*/
function insertStudentData ($conn , $data ) {
$stmt = $conn - > prepare (" INSERT INTO students
(name , email , age) VALUES (? , ? , ?)") ;
$stmt - > bind_param (" ssi", $data [’name ’] ,
$data [’email ’] , $data [’age ’]) ;
try {
$result = $stmt - > execute () ;
return $result ;
} finally {
$stmt - > close () ;
}
}
BIT 3rd Semester Assignment 1 Web Technology
function processStudentSubmission () {
if ( $_SERVER [’ REQUEST_METHOD ’] !== ’POST ’)
die (" Invalid request method .") ;
try {
$conn = connectDatabase () ;
$studentData = validateStudentData ($conn , $_POST ) ;
if ( $studentData === false ) throw new

Exception (" Invalid data ") ;
if ( insertStudentData ($conn ,$studentData ) ) {

echo " Student record added successfully !";

} else {
echo " Failed to add student record .";
}
$conn - > close () ;
} catch ( Exception $e ) {
echo " Error : " . $e - > getMessage () ;
}
}
processStudentSubmission () ;
? >
