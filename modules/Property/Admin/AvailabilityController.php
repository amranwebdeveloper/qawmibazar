<?php
//namespace Modules\Property\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\AdminController;
use Modules\Property\Models\Property;
use Modules\Property\Models\PropertyDate;

class AvailabilityController extends \Modules\Property\Controllers\AvailabilityController
{
    protected $propertyClass;
    /**
     * @var PropertyDate
     */
    protected $propertyDateClass;
    protected $indexView = 'Property::admin.dokan.availability';

    public function __construct()
    {
        parent::__construct();
        $this->setActiveMenu('admin/module/property');
        $this->middleware('dashboard');
    }
    protected function hasPropertyPermission($property_id = false){
        if(empty($property_id)) return false;

        $property = $this->propertyClass::find($property_id);
        if(empty($property)) return false;

        if(!$this->hasPermission('property_manage_others') and $property->create_user != Auth::id()){
            return false;
        }

        $this->currentProperty = $property;
        return true;
    }
}
