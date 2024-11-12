<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Resources\PaginateResource;
use App\Models\Meeting;
use App\Models\MeetingProduct;
use App\Models\MeetingShare;
use App\Models\MeetingSharedProduct;

class MeetingShareController extends Controller {

    public function employees() {
        $roleNames = ['salesmanager', 'coordinators', 'satellite'];
        $roles = Role::whereIn('name', $roleNames)
                ->with(['users' => function ($query) {
                        $query->orderBy('name');
                    }, 'users.employee'])
                ->get();
        $groupedByRole = $roles->mapWithKeys(function ($role) {
            return [
        $role->name => $role->users->map(function ($user) {
            return [
        'id' => $user->id,
        'name' => $user->name,
        'image_url' => !empty($user->employee->image_url) ? asset('storage/' . $user->employee->image_url) : null
            ];
        })
            ];
        });
        return successResponse(trans('api.meeting.created'), $groupedByRole);
    }

    public function share(Request $request, $id) {
        $rules = [
            'type' => 'required|in:normal,shared',
            'meeting_notes' => 'required',
            'products' => 'array',
            'title' => 'required',
            'company_name' => 'required',
            'company_representative' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'location' => 'required',
            'meeting_notes' => 'required',
            'share_to' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }
        try {
            $shareTo = \App\Models\User::findOrFail($request->share_to);
            switch ($request->type) {
                case 'normal':
                    $meeting = Meeting::findOrFail($id);
                    $meeting_id = $meeting->id;
                    $parent_id = null;
                    $products = MeetingProduct::select('product_id', 'supplier_id as brand_id')
                            ->whereIn('id', $request->products)
                            ->map(function ($item) {
                        return $item->toArray();
                    });
                    break;
                case 'shared':
                    $meeting = MeetingShare::findOrFail($id);
                    $meeting_id = $meeting->meeting_id;
                    $parent_id = $id;
                    $products = MeetingSharedProduct::select('product_id', 'supplier_id as brand_id')
                            ->whereIn('id', $request->products)
                            ->map(function ($item) {
                        return $item->toArray();
                    });
                    break;
            }
            //Check this meeting is already shared with 
            $alreadyShared = MeetingShare::where('meeting_id', $meeting_id)
                    ->where('shared_to', $request->share_to)
                    ->where('status', 2)
                    ->first();
            if ($alreadyShared) {
                return errorResponse(trans('api.meeting.already_shared_to', ['name' => $shareTo->name]));
            }
            $shareMeeting = new MeetingShare();
            $shareMeeting->meeting_id = $meeting_id;
            $shareMeeting->shared_by = $request->user()->id;
            $shareMeeting->shared_to = $request->share_to;
            $shareMeeting->parent_id = $parent_id;
            $shareMeeting->title = $request->title;
            $shareMeeting->company_name = $request->company_name;
            $shareMeeting->company_representative = $request->company_representative;
            $shareMeeting->phone = $request->phone;
            $shareMeeting->email = $request->email;
            $shareMeeting->location = $request->location;
            $shareMeeting->business_card = $request->business_card;
            $shareMeeting->meeting_notes = $request->meeting_notes;
            $shareMeeting->save();

            $meeting->status = 2;
            $meeting->save();

            //Save shared products
            $this->insertSharedProducts($shareMeeting->id, $products);
            //Send push notifiction to share_to
            return successResponse(trans('api.meeting.shared'), ['meeting_id' => $shareMeeting->id]);
        } catch (ModelNotFoundException $e) {
            return errorResponse(trans('api.invalid_request'), $e->getMessage());
        }
        return errorResponse(trans('api.invalid_request'));
    }
}
