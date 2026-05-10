@extends('layouts.app')

@section('title', $room->subject_name . ' - LinkLearn')

@section('content')
<div style="width: 100%; max-width: 1000px; margin: 0 auto;">
    {{-- Room Header --}}
    <div style="background: {{ $room->coverPhotoUrl() ? 'url(' . $room->coverPhotoUrl() . ') center/cover no-repeat' : 'var(--brand)' }}; height: 200px; border-radius: 1rem; position: relative; margin-bottom: 2rem; overflow: hidden; display: flex; align-items: flex-end; padding: 2rem; border-bottom: 4px solid var(--accent);">
        <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(15, 23, 42, 0.9) 0%, rgba(15, 23, 42, 0.4) 50%, rgba(15, 23, 42, 0) 100%); z-index: 1;"></div>
        <div style="position: relative; z-index: 2; color: #fff;">
            <h1 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 0.5rem; letter-spacing: -0.02em;">{{ $room->subject_name }}</h1>
            <p style="font-size: 1.1rem; opacity: 0.9; font-weight: 500;">{{ $room->tutor ? $room->tutor->name : 'No Teacher Assigned' }} • {{ $org ? $org->name : 'Personal Classroom' }}</p>
        </div>
    </div>

    @php
        $updateRoute = isset($org) ? route('org.rooms.update', $room->id) : route('rooms.update', $room->id);
        $inviteRoute = isset($org) ? route('org.rooms.invite', $room->id) : route('rooms.invite', $room->id);
        $inviteTeacherRoute = isset($org) ? route('org.rooms.invite-teacher', $room->id) : route('rooms.invite-teacher', $room->id);
        $uploadRoute = isset($org) ? route('org.rooms.upload-file', $room->id) : route('rooms.upload-file', $room->id);
        $purchaseRouteTemplate = isset($org) ? route('org.rooms.purchase-file', [$room->id, ':file_id']) : route('rooms.purchase-file', [$room->id, ':file_id']);
        $approveRouteTemplate = isset($org) ? route('org.rooms.approve-purchase', [$room->id, ':purchase_id']) : route('rooms.approve-purchase', [$room->id, ':purchase_id']);
        $activityStoreRoute = isset($org) ? route('org.rooms.activities.store', $room->id) : route('rooms.activities.store', $room->id);
    @endphp

    @if(session('success'))
        <div style="background: #ecfdf5; color: #065f46; padding: 1rem; border-radius: 0.75rem; margin-bottom: 2rem; border: 1px solid #a7f3d0; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 0.75rem; margin-bottom: 2rem; border: 1px solid #fecaca; font-weight: 600;">
            {{ session('error') }}
        </div>
    @endif

    {{-- Management Section - Admin/Tutor Only --}}
    @if($isAdmin || $isTutor)
        <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            @if($isAdmin || $isTutor)
            <div style="background: #fff; border: 1px solid var(--border); border-radius: 0.75rem; padding: 1.5rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--brand); margin-bottom: 1rem;">Classroom Management</h2>
                <form action="{{ $updateRoute }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 1rem;">
                    @csrf
                    @method('PUT')
                    <div>
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--brand); display: block; margin-bottom: 0.5rem;">Classroom Name</label>
                        <input type="text" name="subject_name" value="{{ old('subject_name', $room->subject_name) }}" required style="width: 100%; padding: 0.85rem; border-radius: 0.75rem; border: 1px solid var(--border); font-size: 0.95rem;">
                    </div>
                    <div>
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--brand); display: block; margin-bottom: 0.5rem;">Description</label>
                        <textarea name="description" rows="3" style="width: 100%; padding: 0.85rem; border-radius: 0.75rem; border: 1px solid var(--border); font-size: 0.95rem;">{{ old('description', $room->description) }}</textarea>
                    </div>
                    <div>
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--brand); display: block; margin-bottom: 0.5rem;">Cover Photo (Optional)</label>
                        <input type="file" name="cover_photo" accept="image/*" style="width: 100%; padding: 0.85rem; border-radius: 0.75rem; border: 1px solid var(--border); font-size: 0.95rem;">
                        <small style="color: var(--text-muted); display: block; margin-top: 0.25rem;">Upload a new image to replace the current cover photo.</small>
                    </div>
                    <button type="submit" class="btn btn-brand" style="padding: 0.9rem;">Save Classroom</button>
                </form>
            </div>
            @endif

            <div style="background: #fff; border: 1px solid var(--border); border-radius: 0.75rem; padding: 1.5rem;">
                @if($isAdmin)
                <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--brand); margin-bottom: 1rem;">Assign Teacher</h2>
                <form action="{{ $inviteTeacherRoute }}" method="POST" style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1rem;">
                    @csrf
                    <div>
                        <label style="font-size: 0.8rem; font-weight: 700; color: var(--brand); display: block; margin-bottom: 0.5rem;">Teacher Email</label>
                        <input type="email" name="email" required placeholder="teacher@example.com" style="width: 100%; padding: 0.85rem; border-radius: 0.75rem; border: 1px solid var(--border); font-size: 0.95rem;">
                        <small style="color: var(--text-muted);">Teacher must have an existing registered account.</small>
                    </div>
                    <button type="submit" class="btn btn-accent" style="padding: 0.9rem;">Assign Teacher</button>
                </form>
                @endif
                
                <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--brand); margin: 0.5rem 0 1rem;">Invite Student</h2>
                <form action="{{ $inviteRoute }}" method="POST" style="display: flex; flex-direction: column; gap: 1rem;">
                    @csrf
                    <div>
                        <input type="email" name="email" required placeholder="student@example.com" style="width: 100%; padding: 0.85rem; border-radius: 0.75rem; border: 1px solid var(--border); font-size: 0.95rem;">
                    </div>
                    <button type="submit" class="btn btn-outline" style="padding: 0.9rem;">Add Student</button>
                </form>
            </div>
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 300px; gap: 2rem;">
        {{-- Main Content --}}
        <div>
            {{-- About Section --}}
            <div style="background: #fff; border: 1px solid var(--border); border-radius: 0.75rem; padding: 1.5rem; margin-bottom: 2rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--brand); margin-bottom: 1rem;">About this Classroom</h2>
                <p style="color: var(--text-muted); line-height: 1.6;">{{ $room->description ?? 'No description available.' }}</p>
            </div>

            @if(($isAdmin || $isTutor) && isset($pendingStudents) && $pendingStudents->count() > 0)
                <div style="background: #fff; border: 1px solid var(--border); border-radius: 0.75rem; padding: 1.5rem; margin-bottom: 2rem; border-left: 4px solid #f59e0b;">
                    <h2 style="font-size: 1.25rem; font-weight: 700; color: #d97706; margin-bottom: 1rem;">Pending Join Requests</h2>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        @foreach($pendingStudents as $student)
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background: #fef3c7; border-radius: 0.5rem;">
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <img src="{{ $student->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($student->name) }}" alt="{{ $student->name }}" style="width: 40px; height: 40px; border-radius: 50%;">
                                    <div>
                                        <p style="font-weight: 700; color: var(--brand); margin: 0;">{{ $student->name }}</p>
                                        <p style="font-size: 0.8rem; color: #92400e; margin: 0;">{{ $student->email }}</p>
                                    </div>
                                </div>
                                <div style="display: flex; gap: 0.5rem;">
                                    <form action="{{ isset($org) ? route('org.rooms.approve-student', [$room->id, $student->id]) : route('rooms.approve-student', [$room->id, $student->id]) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        <button type="submit" class="btn btn-accent" style="padding: 0.4rem 1rem; font-size: 0.85rem;">Approve</button>
                                    </form>
                                    <form action="{{ isset($org) ? route('org.rooms.reject-student', [$room->id, $student->id]) : route('rooms.reject-student', [$room->id, $student->id]) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        <button type="submit" class="btn btn-outline" style="padding: 0.4rem 1rem; font-size: 0.85rem; color: #dc2626; border-color: #fca5a5;">Reject</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Announcements Section --}}
            <div style="background: #fff; border: 1px solid var(--border); border-radius: 0.75rem; padding: 1.5rem; margin-bottom: 2rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--brand);">Announcements</h2>
                    @if($isTutor)
                        <button onclick="document.getElementById('announcement-modal').style.display='flex'" class="btn btn-accent" style="font-size: 0.875rem; padding: 0.5rem 1rem;">+ Post Announcement</button>
                    @endif
                </div>

                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    @forelse($room->announcements()->latest()->get() as $announcement)
                        <div style="border-left: 4px solid var(--accent); background: #f8fafc; padding: 1rem; border-radius: 0 0.5rem 0.5rem 0;">
                            <p style="font-size: 0.95rem; color: var(--text); margin-bottom: 0.5rem; white-space: pre-wrap;">{{ $announcement->content }}</p>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <small style="color: var(--text-muted); font-size: 0.75rem;">Posted on {{ $announcement->created_at->format('M d, Y h:i A') }}</small>
                                @if($isTutor)
                                    <div style="display: flex; gap: 0.5rem;">
                                        <button onclick="openEditAnnouncementModal('{{ $announcement->id }}', '{{ addslashes($announcement->content) }}', '{{ isset($org) ? route('org.rooms.announcements.update', [$room->id, $announcement->id]) : route('rooms.announcements.update', [$room->id, $announcement->id]) }}')" class="btn btn-outline" style="font-size: 0.7rem; padding: 0.2rem 0.5rem;">Edit</button>
                                        <form action="{{ isset($org) ? route('org.rooms.announcements.destroy', [$room->id, $announcement->id]) : route('rooms.announcements.destroy', [$room->id, $announcement->id]) }}" method="POST" onsubmit="return confirm('Delete this announcement?');" style="margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline" style="font-size: 0.7rem; padding: 0.2rem 0.5rem; color: #dc2626; border-color: #fca5a5;">Delete</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p style="text-align: center; color: var(--text-muted); font-size: 0.9rem; margin: 0;">No announcements posted.</p>
                    @endforelse
                </div>
            </div>

            {{-- Activities Section --}}
            <div style="background: #fff; border: 1px solid var(--border); border-radius: 0.75rem; padding: 1.5rem; margin-bottom: 2rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--brand);">Activities</h2>
                    @if($isTutor)
                        <button onclick="document.getElementById('activity-modal').style.display='flex'" class="btn btn-accent" style="font-size: 0.875rem; padding: 0.5rem 1rem;">+ Create Activity</button>
                    @endif
                </div>

                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    @forelse($activities as $activity)
                        <div style="border: 1px solid var(--border); border-radius: 0.75rem; padding: 1.5rem;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                                <div>
                                    <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--brand); margin-bottom: 0.25rem;">{{ $activity->title }}</h3>
                                    <p style="font-size: 0.85rem; color: var(--text-muted);">Deadline: {{ $activity->deadline ? \Carbon\Carbon::parse($activity->deadline)->format('M d, Y h:i A') : 'No deadline' }} {!! $activity->allow_late_submissions ? '<span style="color: #059669; font-weight: 600; margin-left: 0.5rem;">(Late Allowed)</span>' : '<span style="color: #dc2626; font-weight: 600; margin-left: 0.5rem;">(No Late Submissions)</span>' !!}</p>
                                    @if($isTutor)
                                        <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                                            <button onclick="openEditActivityModal('{{ $activity->id }}', '{{ addslashes($activity->title) }}', '{{ addslashes($activity->description) }}', '{{ $activity->deadline ? \Carbon\Carbon::parse($activity->deadline)->format('Y-m-d\TH:i') : '' }}', '{{ $activity->allow_late_submissions }}', '{{ $activity->link }}', '{{ isset($org) ? route('org.rooms.activities.update', $activity->id) : route('rooms.activities.update', $activity->id) }}')" class="btn btn-outline" style="font-size: 0.75rem; padding: 0.25rem 0.75rem;">Edit Activity</button>
                                            <form action="{{ isset($org) ? route('org.rooms.activities.destroy', $activity->id) : route('rooms.activities.destroy', $activity->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this activity? This will also delete all student submissions.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline" style="font-size: 0.75rem; padding: 0.25rem 0.75rem; color: #dc2626; border-color: #fca5a5;">Delete</button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                                @if($isStudent)
                                    @php $submission = $activity->submissions->first(); @endphp
                                    @if($submission)
                                        <div style="text-align: right;">
                                            <span style="background: #ecfdf5; color: #065f46; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 700; display: inline-block; margin-bottom: 0.5rem;">SUBMITTED</span>
                                            @if($submission->grade)
                                                <p style="font-size: 0.9rem; font-weight: 800; color: var(--brand); margin: 0;">Grade: {{ $submission->grade }}</p>
                                            @endif
                                        </div>
                                    @else
                                        @if(!$activity->allow_late_submissions && $activity->deadline && \Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($activity->deadline)))
                                            <div style="text-align: right;">
                                                <span style="background: #fee2e2; color: #991b1b; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 700; display: inline-block;">DEADLINE PASSED</span>
                                            </div>
                                        @else
                                            <button onclick="openSubmitModal('{{ $activity->id }}', '{{ addslashes($activity->title) }}')" class="btn btn-brand" style="font-size: 0.8rem; padding: 0.4rem 1rem;">Submit Answer</button>
                                        @endif
                                    @endif
                                @endif
                            </div>
                            <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 1rem;">{{ $activity->description }}</p>

                            @if($activity->file_path)
                                <div style="margin-bottom: 1rem; background: var(--surface); padding: 0.75rem; border-radius: 0.5rem; display: flex; align-items: center; gap: 0.75rem;">
                                    <span style="font-size: 1.25rem;">📎</span>
                                    <div>
                                        <p style="font-size: 0.85rem; font-weight: 700; color: var(--brand); margin: 0;">Attached Resource</p>
                                    @if(isset($org))
                                        <a href="{{ route('org.rooms.activities.attachment', $activity->id) }}" target="_blank" style="font-size: 0.8rem; color: var(--accent); font-weight: 600;">View Attachment</a>
                                    @else
                                        <a href="{{ route('rooms.activities.attachment', ['activity' => $activity->id, 'org_slug' => $activity->room->organization->slug ?? '']) }}" target="_blank" style="font-size: 0.8rem; color: var(--accent); font-weight: 600;">View Attachment</a>
                                    @endif
                                    </div>
                                </div>
                            @endif

                            @if($activity->link)
                                <div style="margin-bottom: 1rem; background: #eef2ff; padding: 0.75rem; border-radius: 0.5rem; display: flex; align-items: center; gap: 0.75rem;">
                                    <span style="font-size: 1.25rem;">🔗</span>
                                    <div>
                                        <p style="font-size: 0.85rem; font-weight: 700; color: #4338ca; margin: 0;">Attached Link</p>
                                        <a href="{{ $activity->link }}" target="_blank" style="font-size: 0.8rem; color: #4f46e5; font-weight: 600;">Visit External Link</a>
                                    </div>
                                </div>
                            @endif

                            @if($isTutor && $activity->submissions->count() > 0)
                                <div style="margin-top: 1.5rem; border-top: 1px solid var(--border); padding-top: 1rem;">
                                    <h4 style="font-size: 0.9rem; font-weight: 700; color: var(--brand); margin-bottom: 1rem;">Student Submissions</h4>
                                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                                        @foreach($activity->submissions as $sub)
                                            <div style="background: #f8fafc; padding: 1rem; border-radius: 0.5rem; display: flex; justify-content: space-between; align-items: center;">
                                                <div>
                                                    <p style="font-weight: 700; color: var(--brand); margin: 0;">{{ $sub->student->name }}</p>
                                                    <a href="{{ asset('storage/' . $sub->file_path) }}" target="_blank" style="font-size: 0.8rem; color: var(--accent); font-weight: 600;">View Submission</a>
                                                </div>
                                                <div style="display: flex; align-items: center; gap: 1rem;">
                                                    @if($sub->grade)
                                                        <span style="font-weight: 800; color: var(--brand);">Grade: {{ $sub->grade }}</span>
                                                    @endif
                                                    <button onclick="openGradeModal('{{ $sub->id }}', '{{ $sub->student->name }}')" class="btn btn-outline" style="font-size: 0.75rem; padding: 0.3rem 0.75rem;">{{ $sub->grade ? 'Edit Grade' : 'Give Grade' }}</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <p style="text-align: center; color: var(--text-muted); padding: 2rem; border: 1px dashed var(--border); border-radius: 0.5rem;">No activities posted yet.</p>
                    @endforelse
                </div>
            </div>

            {{-- File Section --}}
            <div style="background: #fff; border: 1px solid var(--border); border-radius: 0.75rem; padding: 1.5rem; margin-bottom: 2rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--brand);">Classroom Files</h2>
                    @if($isTutor)
                        <button onclick="document.getElementById('upload-modal').style.display='flex'" class="btn btn-accent" style="font-size: 0.875rem; padding: 0.5rem 1rem;">+ Upload File</button>
                    @endif
                </div>

                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    @forelse($files as $file)
                        @php
                            $purchase = $file->purchases()->where('user_id', auth()->id())->first();
                            $isPaid = $purchase && $purchase->status === 'completed';
                            $isPending = $purchase && $purchase->status === 'pending';
                        @endphp
                        <div style="border: 1px solid var(--border); border-radius: 0.5rem; padding: 1rem; display: flex; justify-content: space-between; align-items: center; transition: 0.2s;" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--border)'">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <div style="background: var(--brand-soft); color: var(--brand); width: 40px; height: 40px; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                                    @if($isPaid || $isTutor || $isAdmin)
                                        📄
                                    @else
                                        🔒
                                    @endif
                                </div>
                                <div>
                                    <h4 style="font-weight: 700; color: var(--brand); margin: 0;">{{ $file->title }}</h4>
                                    <p style="font-size: 0.8rem; color: var(--text-muted); margin: 0;">
                                        @if($isTutor || $isAdmin)
                                            {{-- Do not show lock status for the owner/admin --}}
                                        @elseif($isPaid)
                                            <span style="color: #059669; font-weight: 600;">Unlocked</span>
                                        @elseif($isPending)
                                            <span style="color: #d97706; font-weight: 600;">Pending Confirmation</span>
                                        @else
                                            <span style="color: #dc2626; font-weight: 600;">Locked (₱{{ number_format($file->price, 2) }})</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            <div>
                                @if($isPaid || $isTutor || $isAdmin)
                                    <div style="display: flex; gap: 0.5rem;">
                                        <a href="{{ $org ? route('org.rooms.preview-file', $file->id) : route('rooms.preview-file', $file->id) }}" target="_blank" class="btn btn-outline" style="font-size: 0.8rem; padding: 0.4rem 1rem;">Preview</a>
                                        <a href="{{ $org ? route('org.rooms.download-file', $file->id) : route('rooms.download-file', $file->id) }}" class="btn btn-brand" style="font-size: 0.8rem; padding: 0.4rem 1rem;">Download</a>
                                    </div>
                                @elseif($isPending)
                                    <button disabled style="background: #f3f4f6; color: #9ca3af; border: 1px solid #e5e7eb; padding: 0.4rem 1rem; border-radius: 0.5rem; font-size: 0.8rem; font-weight: 600; cursor: not-allowed;">Processing...</button>
                                @else
                                    <button onclick="openPurchaseModal('{{ $file->id }}', '{{ $file->title }}', '{{ $file->price }}')" class="btn btn-accent" style="font-size: 0.8rem; padding: 0.4rem 1rem;">Unlock File</button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p style="text-align: center; color: var(--text-muted); padding: 2rem; border: 1px dashed var(--border); border-radius: 0.5rem;">No files uploaded yet.</p>
                    @endforelse
                </div>
            </div>

            {{-- Pending Approvals (For Tutor Only) --}}
            @if($isTutor)
                @php
                    $pendingPurchases = \App\Models\FilePurchase::whereIn('file_id', $room->files->pluck('id'))
                        ->where('status', 'pending')
                        ->with(['user', 'file'])
                        ->get();
                @endphp

                @if($pendingPurchases->count() > 0)
                    <div style="background: #fff; border: 1px solid var(--border); border-radius: 0.75rem; padding: 1.5rem;">
                        <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--brand); margin-bottom: 1.5rem;">Pending File Approvals</h2>
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            @foreach($pendingPurchases as $pending)
                                <div style="border: 1px solid var(--border); border-radius: 0.5rem; padding: 1rem; display: flex; justify-content: space-between; align-items: center;">
                                    <div style="display: flex; align-items: center; gap: 1rem;">
                                        <img src="{{ $pending->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($pending->user->name) }}" style="width: 40px; height: 40px; border-radius: 50%;">
                                        <div>
                                            <h4 style="font-weight: 700; color: var(--brand); margin: 0;">{{ $pending->user->name }}</h4>
                                            <p style="font-size: 0.8rem; color: var(--text-muted); margin: 0;">Purchased: {{ $pending->file->title }}</p>
                                        </div>
                                    </div>
                                    <div style="display: flex; gap: 0.5rem;">
                                        @if($org)
                                            <a href="{{ route('org.rooms.tenant-proof', ['path' => $pending->proof_of_payment]) }}" target="_blank" class="btn" style="background: #f3f4f6; color: var(--brand); font-size: 0.8rem; padding: 0.4rem 1rem; border: 1px solid var(--border);">View Proof</a>
                                        @else
                                            <a href="{{ asset('storage/' . $pending->proof_of_payment) }}" target="_blank" class="btn" style="background: #f3f4f6; color: var(--brand); font-size: 0.8rem; padding: 0.4rem 1rem; border: 1px solid var(--border);">View Proof</a>
                                        @endif
                                        <form action="{{ $org ? route('org.rooms.approve-purchase', [$room->id, $pending->id]) : route('rooms.approve-purchase', [$room->id, $pending->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-accent" style="font-size: 0.8rem; padding: 0.4rem 1rem;">Approve</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif
        </div>

        {{-- Sidebar --}}
        <div>
            <div style="background: #fff; border: 1px solid var(--border); border-radius: 0.75rem; padding: 1.5rem; position: sticky; top: 2rem;">
                <h3 style="font-size: 1rem; font-weight: 700; color: var(--brand); margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.05em;">Class Details</h3>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.9rem;">
                        <span style="color: var(--text-muted);">Tutor</span>
                        <span style="font-weight: 600;">{{ $room->tutor ? $room->tutor->name : 'Unassigned' }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.9rem;">
                        <span style="color: var(--text-muted);">Students</span>
                        <span style="font-weight: 600;">{{ $room->students->count() }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.9rem;">
                        <span style="color: var(--text-muted);">Status</span>
                        <span style="background: #ecfdf5; color: #065f46; padding: 0.1rem 0.5rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 700;">{{ strtoupper($room->status) }}</span>
                    </div>
                </div>
            </div>

            @if(($isTutor || $isAdmin) && $room->students->count() > 0)
                <div style="background: #fff; border: 1px solid var(--border); border-radius: 0.75rem; padding: 1.5rem; margin-top: 1.5rem;">
                    <h3 style="font-size: 1rem; font-weight: 700; color: var(--brand); margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.05em;">Enrolled Students</h3>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        @foreach($room->students as $student)
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;">
                                <div style="display: flex; align-items: center; gap: 0.5rem; overflow: hidden;">
                                    <img src="{{ $student->profile_picture ? asset('storage/' . $student->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($student->name) . '&background=6366f1&color=fff' }}" alt="{{ $student->name }}" style="width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0;">
                                    <div style="overflow: hidden;">
                                        <p style="font-weight: 700; color: var(--brand); margin: 0; font-size: 0.85rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $student->name }}</p>
                                        <p style="font-size: 0.75rem; color: var(--text-muted); margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $student->email }}</p>
                                    </div>
                                </div>
                                <form action="{{ isset($org) ? route('org.rooms.remove-student', [$room->id, $student->id]) : route('rooms.remove-student', [$room->id, $student->id]) }}" method="POST" onsubmit="return confirm('Remove {{ $student->name }} from this classroom?');" style="flex-shrink: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Remove student" style="background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; border-radius: 0.4rem; padding: 0.2rem 0.5rem; cursor: pointer; font-size: 0.75rem; font-weight: 700; transition: 0.2s;" onmouseover="this.style.background='#fecaca'" onmouseout="this.style.background='#fee2e2'">
                                        Remove
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Modals --}}

{{-- Upload File Modal --}}
<div id="upload-modal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(4px); z-index: 50; align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: #fff; width: 100%; max-width: 500px; border-radius: 1rem; overflow: hidden;">
        <div style="padding: 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-weight: 800; color: var(--brand); margin: 0;">Upload Classroom File</h3>
            <button onclick="document.getElementById('upload-modal').style.display='none'" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted);">&times;</button>
        </div>
        <form action="{{ $uploadRoute }}" method="POST" enctype="multipart/form-data" style="padding: 1.5rem;">
            @csrf
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--brand); margin-bottom: 0.5rem;">File Title</label>
                <input type="text" name="title" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.5rem;">
            </div>
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--brand); margin-bottom: 0.5rem;">Price (₱)</label>
                <input type="number" name="price" required min="0" value="0" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.5rem;">
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--brand); margin-bottom: 0.5rem;">Select File (Max 10MB)</label>
                <input type="file" name="file" required style="width: 100%; padding: 0.5rem; border: 1px solid var(--border); border-radius: 0.5rem;">
            </div>
            <button type="submit" class="btn btn-brand" style="width: 100%; padding: 0.75rem;">Upload and Lock</button>
        </form>
    </div>
</div>

{{-- Activity Modal --}}
<div id="activity-modal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(4px); z-index: 50; align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: #fff; width: 100%; max-width: 500px; border-radius: 1rem; overflow: hidden;">
        <div style="padding: 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-weight: 800; color: var(--brand); margin: 0;">Create Activity</h3>
            <button onclick="document.getElementById('activity-modal').style.display='none'" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted);">&times;</button>
        </div>
        <form action="{{ $activityStoreRoute }}" method="POST" enctype="multipart/form-data" style="padding: 1.5rem;">
            @csrf
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--brand); margin-bottom: 0.5rem;">Activity Title</label>
                <input type="text" name="title" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.5rem;">
            </div>
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--brand); margin-bottom: 0.5rem;">Description</label>
                <textarea name="description" rows="3" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.5rem;"></textarea>
            </div>
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--brand); margin-bottom: 0.5rem;">Attach File (PDF/Word, Optional)</label>
                <input type="file" name="file" style="width: 100%; padding: 0.5rem; border: 1px solid var(--border); border-radius: 0.5rem;">
            </div>
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--brand); margin-bottom: 0.5rem;">Attach Link (Optional)</label>
                <input type="url" name="link" placeholder="https://" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.5rem;">
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--brand); margin-bottom: 0.5rem;">Deadline</label>
                <input type="datetime-local" name="deadline" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.5rem;">
            </div>
            <div style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <input type="checkbox" name="allow_late_submissions" id="allow_late" value="1" checked style="width: 1rem; height: 1rem; cursor: pointer;">
                <label for="allow_late" style="font-size: 0.875rem; font-weight: 600; color: var(--brand); cursor: pointer;">Allow late submissions</label>
            </div>
            <button type="submit" class="btn btn-brand" style="width: 100%; padding: 0.75rem;">Post Activity</button>
        </form>
    </div>
</div>

{{-- Edit Activity Modal --}}
<div id="edit-activity-modal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(4px); z-index: 50; align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: #fff; width: 100%; max-width: 500px; border-radius: 1rem; overflow: hidden;">
        <div style="padding: 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-weight: 800; color: var(--brand); margin: 0;">Edit Activity</h3>
            <button type="button" onclick="document.getElementById('edit-activity-modal').style.display='none'" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted);">&times;</button>
        </div>
        <form id="edit-activity-form" method="POST" enctype="multipart/form-data" style="padding: 1.5rem;">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--brand); margin-bottom: 0.5rem;">Activity Title</label>
                <input type="text" name="title" id="edit_activity_title" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.5rem;">
            </div>
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--brand); margin-bottom: 0.5rem;">Description</label>
                <textarea name="description" id="edit_activity_description" rows="3" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.5rem;"></textarea>
            </div>
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--brand); margin-bottom: 0.5rem;">Replace Attached File (Optional)</label>
                <input type="file" name="file" style="width: 100%; padding: 0.5rem; border: 1px solid var(--border); border-radius: 0.5rem;">
                <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem; margin-bottom: 0;">Leave empty to keep current file.</p>
            </div>
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--brand); margin-bottom: 0.5rem;">Attach Link (Optional)</label>
                <input type="url" name="link" id="edit_activity_link" placeholder="https://" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.5rem;">
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--brand); margin-bottom: 0.5rem;">Deadline</label>
                <input type="datetime-local" name="deadline" id="edit_activity_deadline" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.5rem;">
            </div>
            <div style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <input type="checkbox" name="allow_late_submissions" id="edit_allow_late" value="1" style="width: 1rem; height: 1rem; cursor: pointer;">
                <label for="edit_allow_late" style="font-size: 0.875rem; font-weight: 600; color: var(--brand); cursor: pointer;">Allow late submissions</label>
            </div>
            <button type="submit" class="btn btn-brand" style="width: 100%; padding: 0.75rem;">Save Changes</button>
        </form>
    </div>
</div>

{{-- Submit Answer Modal --}}
<div id="submit-modal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(4px); z-index: 50; align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: #fff; width: 100%; max-width: 500px; border-radius: 1rem; overflow: hidden;">
        <div style="padding: 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-weight: 800; color: var(--brand); margin: 0;">Submit Answer</h3>
            <button onclick="document.getElementById('submit-modal').style.display='none'" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted);">&times;</button>
        </div>
        <form id="submit-form" method="POST" enctype="multipart/form-data" style="padding: 1.5rem;">
            @csrf
            <p style="font-weight: 700; color: var(--brand); margin-bottom: 1rem;" id="submit-title"></p>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--brand); margin-bottom: 0.5rem;">Select File (Max 10MB)</label>
                <input type="file" name="file" required style="width: 100%; padding: 0.5rem; border: 1px solid var(--border); border-radius: 0.5rem;">
            </div>
            <button type="submit" class="btn btn-brand" style="width: 100%; padding: 0.75rem;">Submit Now</button>
        </form>
    </div>
</div>

{{-- Grade Modal --}}
<div id="grade-modal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(4px); z-index: 50; align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: #fff; width: 100%; max-width: 500px; border-radius: 1rem; overflow: hidden;">
        <div style="padding: 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-weight: 800; color: var(--brand); margin: 0;">Give Grade</h3>
            <button onclick="document.getElementById('grade-modal').style.display='none'" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted);">&times;</button>
        </div>
        <form id="grade-form" method="POST" style="padding: 1.5rem;">
            @csrf
            <p style="font-weight: 700; color: var(--brand); margin-bottom: 1rem;" id="grade-student"></p>
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--brand); margin-bottom: 0.5rem;">Grade (e.g. 95/100 or A)</label>
                <input type="text" name="grade" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.5rem;">
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--brand); margin-bottom: 0.5rem;">Feedback (Optional)</label>
                <textarea name="feedback" rows="3" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.5rem;"></textarea>
            </div>
            <button type="submit" class="btn btn-brand" style="width: 100%; padding: 0.75rem;">Save Grade</button>
        </form>
    </div>
</div>

{{-- Purchase Modal --}}
<div id="purchase-modal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(4px); z-index: 50; align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: #fff; width: 100%; max-width: 480px; border-radius: 1rem; overflow: hidden; max-height: 90vh; overflow-y: auto;">
        <div style="padding: 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: linear-gradient(135deg, #00b4d8, #0077b6);">
            <div>
                <h3 style="font-weight: 800; color: #fff; margin: 0;">💳 Unlock File</h3>
                <p id="purchase-file-name" style="color: rgba(255,255,255,0.85); font-size: 0.85rem; margin: 0.25rem 0 0;"></p>
            </div>
            <button onclick="document.getElementById('purchase-modal').style.display='none'" style="background: rgba(255,255,255,0.2); border: none; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; color: #fff; font-size: 1.2rem; display: flex; align-items: center; justify-content: center;">&times;</button>
        </div>

        {{-- GCash QR Code Section --}}
        @php
            $gcashOrg = isset($org) ? $org : (function_exists('tenant') && tenant() ? tenant() : null);
        @endphp
        <div style="padding: 1.25rem 1.5rem; background: #f0f9ff; border-bottom: 1px solid #bae6fd;">
            <p style="font-size: 0.8rem; font-weight: 700; color: #0369a1; margin: 0 0 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">📱 Scan to Pay via GCash</p>
            @if($gcashOrg && $gcashOrg->gcash_qr_code)
                <div style="text-align: center;">
                    @php
                        $centralDomains = config('tenancy.central_domains', ['localhost']);
                        $centralDomain  = $centralDomains[0];
                        $port = request()->getPort();
                        $portStr = ($port && $port != 80 && $port != 443) ? ':' . $port : '';
                        $gcashQrUrl = request()->getScheme() . '://' . $centralDomain . $portStr . '/org-qr/' . $gcashOrg->slug;
                    @endphp
                    <img src="{{ $gcashQrUrl }}" alt="GCash QR Code" style="max-width: 180px; border-radius: 0.75rem; border: 3px solid #00b4d8; padding: 0.5rem; background: #fff;">
                    <p style="font-size: 0.8rem; color: #0284c7; font-weight: 600; margin: 0.5rem 0 0;">Scan this QR code with your GCash app to pay</p>
                </div>
            @else
                <div style="text-align: center; padding: 1rem; border: 2px dashed #bae6fd; border-radius: 0.75rem;">
                    <p style="color: #0284c7; font-size: 0.85rem; font-weight: 600; margin: 0;">No GCash QR Code set yet. Please contact your teacher for payment details.</p>
                </div>
            @endif
        </div>

        <div style="padding: 0.75rem 1.5rem; background: #fffbeb; border-bottom: 1px solid #fde68a;">
            <p style="margin: 0; font-size: 0.85rem; color: #92400e; font-weight: 600;">💡 After paying, upload your screenshot below as proof of payment.</p>
        </div>

        <form id="purchase-form" method="POST" enctype="multipart/form-data" style="padding: 1.5rem;">
            @csrf
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--brand); margin-bottom: 0.5rem;">📎 Upload Screenshot / Proof of Payment</label>
                <input type="file" name="proof_of_payment" accept="image/*" required style="width: 100%; padding: 0.5rem; border: 1px solid var(--border); border-radius: 0.5rem;">
            </div>
            <button type="submit" style="width: 100%; padding: 0.875rem; background: linear-gradient(135deg, #00b4d8, #0077b6); color: #fff; border: none; border-radius: 0.75rem; font-weight: 700; font-size: 0.95rem; cursor: pointer; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">Submit Proof of Payment</button>
        </form>
    </div>
</div>


{{-- Post Announcement Modal --}}
<div id="announcement-modal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(4px); z-index: 50; align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: #fff; width: 100%; max-width: 500px; border-radius: 1rem; overflow: hidden;">
        <div style="padding: 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-weight: 800; color: var(--brand); margin: 0;">Post Announcement</h3>
            <button onclick="document.getElementById('announcement-modal').style.display='none'" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted);">&times;</button>
        </div>
        <form action="{{ isset($org) ? route('org.rooms.announcements.store', $room->id) : route('rooms.announcements.store', $room->id) }}" method="POST" style="padding: 1.5rem;">
            @csrf
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--brand); margin-bottom: 0.5rem;">Announcement Content</label>
                <textarea name="content" required rows="5" placeholder="Write your announcement here..." style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.5rem; font-family: inherit;"></textarea>
            </div>
            <button type="submit" class="btn btn-brand" style="width: 100%; padding: 0.75rem;">Post Announcement</button>
        </form>
    </div>
</div>

{{-- Edit Announcement Modal --}}
<div id="edit-announcement-modal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(4px); z-index: 50; align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: #fff; width: 100%; max-width: 500px; border-radius: 1rem; overflow: hidden;">
        <div style="padding: 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-weight: 800; color: var(--brand); margin: 0;">Edit Announcement</h3>
            <button type="button" onclick="document.getElementById('edit-announcement-modal').style.display='none'" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted);">&times;</button>
        </div>
        <form id="edit-announcement-form" method="POST" style="padding: 1.5rem;">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--brand); margin-bottom: 0.5rem;">Announcement Content</label>
                <textarea name="content" id="edit_announcement_content" required rows="5" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 0.5rem; font-family: inherit;"></textarea>
            </div>
            <button type="submit" class="btn btn-brand" style="width: 100%; padding: 0.75rem;">Save Changes</button>
        </form>
    </div>
</div>

<script>
    var submitRouteBase = '{{ isset($org) ? url('/activities') : url('/activities') }}';
    var gradeRouteBase = '{{ isset($org) ? url('/submissions') : url('/submissions') }}';

    function openSubmitModal(activityId, title) {
        document.getElementById('submit-title').innerText = title;
        document.getElementById('submit-form').action = submitRouteBase + '/' + activityId + '/submit';
        document.getElementById('submit-modal').style.display = 'flex';
    }

    function openGradeModal(submissionId, studentName) {
        document.getElementById('grade-student').innerText = 'Student: ' + studentName;
        document.getElementById('grade-form').action = gradeRouteBase + '/' + submissionId + '/grade';
        document.getElementById('grade-modal').style.display = 'flex';
    }

    function openPurchaseModal(fileId, title, price) {
        const form = document.getElementById('purchase-form');
        const url = '{{ $purchaseRouteTemplate }}'.replace(':file_id', fileId);
        form.setAttribute('data-action', url);
        const fileNameEl = document.getElementById('purchase-file-name');
        if (fileNameEl) fileNameEl.textContent = title + ' \u2014 \u20b1' + parseFloat(price).toFixed(2);
        document.getElementById('purchase-modal').style.display = 'flex';
    }

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('purchase-form');
        if (!form) return;

        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const action = form.getAttribute('data-action');
            if (!action) {
                alert('Error: No purchase action set. Please close and re-open the modal.');
                return;
            }

            const fileInput = form.querySelector('input[name="proof_of_payment"]');
            if (!fileInput || !fileInput.files.length) {
                alert('Please select an image file first.');
                return;
            }

            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';

            const formData = new FormData(form);
            const token = document.querySelector('meta[name="csrf-token"]')?.content
                || form.querySelector('input[name="_token"]')?.value;

            try {
                const headers = { 'Accept': 'application/json' };
                if (token) headers['X-CSRF-TOKEN'] = token;

                const response = await fetch(action, {
                    method: 'POST',
                    headers: headers,
                    body: formData,
                });

                if (response.ok) {
                    document.getElementById('purchase-modal').style.display = 'none';
                    // Show a success banner
                    const banner = document.createElement('div');
                    banner.style = 'position:fixed;top:1rem;right:1rem;background:#ecfdf5;color:#065f46;padding:1rem 1.5rem;border-radius:0.75rem;border:1px solid #a7f3d0;font-weight:700;z-index:9999;box-shadow:0 4px 12px rgba(0,0,0,.1)';
                    banner.textContent = '✅ Proof of payment submitted! Waiting for tutor confirmation.';
                    document.body.appendChild(banner);
                    setTimeout(() => banner.remove(), 5000);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    const text = await response.text();
                    console.error('Server response:', response.status, text);
                    if (response.status === 419) {
                        alert('Your session has expired. Please refresh the page and try again.');
                    } else if (response.status === 422) {
                        alert('Validation error. Please make sure you selected an image file (jpg, png).');
                    } else {
                        alert('An error occurred (HTTP ' + response.status + '). Please try again or refresh the page.');
                    }
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            } catch (err) {
                console.error('Fetch error:', err);
                alert('Network error. Please check your connection and try again.');
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });
    });

    function openEditActivityModal(id, title, description, deadline, allowLate, link, actionUrl) {
        document.getElementById('edit_activity_title').value = title;
        document.getElementById('edit_activity_description').value = description;
        document.getElementById('edit_activity_deadline').value = deadline;
        document.getElementById('edit_allow_late').checked = allowLate == '1';
        document.getElementById('edit_activity_link').value = link;
        document.getElementById('edit-activity-form').action = actionUrl;
        document.getElementById('edit-activity-modal').style.display = 'flex';
    }

    function openEditAnnouncementModal(id, content, actionUrl) {
        document.getElementById('edit_announcement_content').value = content;
        document.getElementById('edit-announcement-form').action = actionUrl;
        document.getElementById('edit-announcement-modal').style.display = 'flex';
    }
</script>
@endsection
