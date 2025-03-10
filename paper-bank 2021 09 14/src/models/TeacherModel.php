<?php


use Illuminate\Database\Eloquent\Model;


class TeacherModel extends Model
{
  protected $table = "sms_employees";

  protected $primaryKey = "teacher_id";

  protected $fillable = [
    'teacher_name', 'gender', 'dob', 'email', 'teacher_cnic',
    'salary', 'phone', 'address', 'image'
  ];

  public function saveData($data)
  {
    $filename = NULL;
    $isFileUploaded = false;

    // Upload the file to the server
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Check if file was uploaded without errors
      if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["photo"]["name"];
        $filetype = $_FILES["photo"]["type"];
        $filesize = $_FILES["photo"]["size"];

        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");

        // Verify file size - 5MB maximum
        $maxsize = 5 * 1024 * 1024;
        if ($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");

        $fileExtention = pathinfo($filename, PATHINFO_EXTENSION);
        $filename = date('Y-m-d-H-i-s') . $data['roll_num'] . "." . $fileExtention;

        move_uploaded_file($_FILES["photo"]["tmp_name"], "uploads/" . $filename);

        $data['image'] = $filename;
      }

      return $this->create($data);
    }
  }


  // public function section()
  // {
  //   return $this->hasOne(SectionModel::class, 'section_id', 'section_id');
  // }

  // public function class()
  // {
  //   return $this->hasOne(ClassesModel::class, 'class_id', 'class_id');
  // }

  // public function updateData($id, $data)
  // {
  //   try {
  //     $student = StudentModel::where("student_id", $id)->first();


  //     $filename = NULL;
  //     $isFileUploaded = false;

  //     // Upload the file to the server
  //     if ($_SERVER["REQUEST_METHOD"] == "POST") {
  //       // Check if file was uploaded without errors
  //       if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {


  //         $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
  //         $filename = $_FILES["photo"]["name"];
  //         $filetype = $_FILES["photo"]["type"];
  //         $filesize = $_FILES["photo"]["size"];

  //         // Verify file extension
  //         $ext = pathinfo($filename, PATHINFO_EXTENSION);
  //         if (!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");

  //         // Verify file size - 5MB maximum
  //         $maxsize = 5 * 1024 * 1024;
  //         if ($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");

  //         $fileExtention = pathinfo($filename, PATHINFO_EXTENSION);
  //         $filename = date('Y-m-d-H-i-s') . $data['roll_num'] . "." . $fileExtention;

  //         move_uploaded_file($_FILES["photo"]["tmp_name"], "uploads/" . $filename);


  //         // Delete the previous image
  //         unlink(APPROOT . "/../public/uploads/$student->image");

  //         // Reassign the image
  //         $student->image = $filename;
  //       }
  //     }

  //     $student->teacher_name = $data['teacher_name'];
  //     $student->guardian_name = $data['guardian_name'];
  //     $student->gender = $data['gender'];
  //     $student->dob = $data['dob'];
  //     $student->phone = $data['phone'];
  //     $student->section_id = $data['section_id'];
  //     $student->roll_num = $data['roll_num'];
  //     $student->city = $data['city'];
  //     $student->admission_date = $data['admission_date'];
  //     $student->address = $data['address'];
  //     $student->class_id = $data['class_id'];
  //     $student->status = $data['status'];

  //     return $student->save();
  //   } catch (Exception $ex) {
  //     dump($ex);
  //   }
  // }
}
