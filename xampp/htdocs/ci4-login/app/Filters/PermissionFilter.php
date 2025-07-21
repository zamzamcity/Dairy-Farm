namespace App\Filters;

use App\Models\PermissionGroupPermissionModel;
use App\Models\PermissionModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class PermissionFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if ($session->has('user_id')) {
            $userModel = new \App\Models\UserModel();
            $user = $userModel->find($session->get('user_id'));

            if ($user && $user['permission_group_id']) {
                $pgpModel = new PermissionGroupPermissionModel();
                $permissionIds = $pgpModel->where('permission_group_id', $user['permission_group_id'])->findColumn('permission_id');

                if ($permissionIds) {
                    $permModel = new PermissionModel();
                    $permissions = $permModel->whereIn('id', $permissionIds)->findAll();

                    $names = array_column($permissions, 'name');
                    session()->set('user_permissions', $names);
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}