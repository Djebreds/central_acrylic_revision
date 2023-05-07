<?php 

class Cashier extends Controller {

    public function __construct() {
        Validate::authCashier($_SESSION['division']);
        $_SESSION['products_count'] = count($this->model('Product')->get());
        $_SESSION['orders_count'] = count($this->model('Order')->get());
        $_SESSION['finish_count'] = count($this->model('Order')->getFinish());
        $_SESSION['notifications'] = $this->model('Notification')->getNotification();
    }

    public function index() {
        $data['title'] = 'Dashboard Kasir';
        $data['products'] = $this->model('Product')->get();
        $data['machine_process'] = $this->model('MachineProcess')->get();

        $this->view('staff/layouts/header', $data);
        $this->view('staff/cashier/dashboard/index', $data);
        $this->view('staff/layouts/footer');
    }

    public function setting() {
        $data['title'] = 'Setting';
        $this->view('staff/layouts/header', $data);
        $this->view('staff/cashier/setting/index');
        $this->view('staff/layouts/footer');
    }

    public function updatePassword() {
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['password_confirmation'];

        if ($newPassword != $confirmPassword) {
            $_SESSION['error_new_password'] = 'password yang anda masukkan tidak cocok';
            Flasher::setNotyf('Password gagal diubah', 'danger');
            Flasher::setFlash('password yang anda masukkan tidak cocok', 'danger');

            return redirectTo('cashier/setting/index');
        } else if ($this->model('Staff')->updatePassword($_POST)) {
            Flasher::setNotyf('Password berhasil diubah', 'success');
            
            return redirectTo('cashier/setting/index');   
        } else {
            Flasher::setNotyf('Password gagal diubah', 'danger');

            return redirectTo('cashier/setting/index');
        }
    }

    public function checkNotifications() {
        $data['id_staff'] = $_SESSION['id'];
        $notifications = $this->model('Notification')->checkNotifications($data);
        
        echo json_encode($notifications);
    }

    public function markNotifications() {
        $data['id_staff'] = $_SESSION['id'];
        $data['is_read'] = 1;

        $this->model('Notification')->markNotifications($data);

        echo json_encode(array('success' => true));
    }

}