<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - Owner</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                        },
                        success: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                        },
                        warning: {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309',
                        },
                        danger: {
                            50: '#fef2f2',
                            100: '#fee2e2',
                            500: '#ef4444',
                            600: '#dc2626',
                            700: '#b91c1c',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .card-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .hover-lift {
            transition: all 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .slide-in {
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .avatar {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: white;
        }
        
        .avatar-owner {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }
        
        .avatar-admin {
            background: linear-gradient(135deg, #10b981, #059669);
        }
        
        .avatar-karyawan {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header dengan Breadcrumb -->
        <div class="mb-8 fade-in">
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('owner.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors">
                            <i class="fas fa-home mr-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Manajemen User</span>
                        </div>
                    </li>
                </ol>
            </nav>
            
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Manajemen User</h1>
                    <p class="text-gray-600 mt-1">Kelola data karyawan dan akses sistem</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('owner.dashboard') }}" class="bg-white text-gray-700 px-4 py-2.5 rounded-lg hover:bg-gray-50 transition-all duration-200 flex items-center card-shadow hover-lift border border-gray-200">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <button onclick="openAddUserModal()" class="bg-success-600 text-white px-4 py-2.5 rounded-lg hover:bg-success-700 transition-all duration-200 flex items-center card-shadow hover-lift">
                        <i class="fas fa-plus mr-2"></i>Tambah User
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 fade-in">
            <div class="bg-white rounded-xl card-shadow p-6 border-l-4 border-primary-500 hover-lift">
                <div class="flex items-center">
                    <div class="p-3 bg-primary-100 rounded-lg">
                        <i class="fas fa-users text-primary-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $statistics['total_users'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl card-shadow p-6 border-l-4 border-warning-500 hover-lift">
                <div class="flex items-center">
                    <div class="p-3 bg-warning-100 rounded-lg">
                        <i class="fas fa-crown text-warning-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Owner</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $statistics['total_owners'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl card-shadow p-6 border-l-4 border-success-500 hover-lift">
                <div class="flex items-center">
                    <div class="p-3 bg-success-100 rounded-lg">
                        <i class="fas fa-user-shield text-success-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Admin/Mandor</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $statistics['total_admins'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl card-shadow p-6 border-l-4 border-purple-500 hover-lift">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <i class="fas fa-user-hard-hat text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Karyawan</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $statistics['total_karyawans'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- User List -->
        <div class="bg-white rounded-xl card-shadow overflow-hidden fade-in">
            <div class="px-6 py-5 border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Pengguna</h3>
                        <p class="text-gray-500 text-sm mt-1">Kelola semua pengguna sistem</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" id="searchUser" placeholder="Cari pengguna..." 
                                   class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 w-full md:w-64 transition-colors">
                        </div>
                        <button onclick="exportUsers()" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-all duration-200 flex items-center card-shadow hover-lift whitespace-nowrap">
                            <i class="fas fa-download mr-2"></i>Export
                        </button>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akses</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="userTableBody">
                            @foreach($users as $user)
                            <tr class="user-row hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="avatar @if($user->role === 'owner') avatar-owner @elseif($user->role === 'admin') avatar-admin @else avatar-karyawan @endif">
                                            {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900">{{ $user->nama_lengkap }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->username }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $user->no_telepon ?? '-' }}</div>
                                    <div class="text-xs text-gray-500 capitalize">{{ $user->status_tinggal ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capitalize">
                                    {{ str_replace('_', ' ', $user->jabatan) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->role === 'owner')
                                        <span class="status-badge bg-warning-100 text-warning-800">
                                            <i class="fas fa-crown mr-1"></i>Owner
                                        </span>
                                    @elseif($user->role === 'admin')
                                        <span class="status-badge bg-success-100 text-success-800">
                                            <i class="fas fa-user-shield mr-1"></i>Admin
                                        </span>
                                    @else
                                        <span class="status-badge bg-blue-100 text-blue-800">
                                            <i class="fas fa-user-hard-hat mr-1"></i>Karyawan
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->status_aktif)
                                        <span class="status-badge bg-success-100 text-success-800">
                                            <i class="fas fa-check-circle mr-1"></i>Aktif
                                        </span>
                                    @else
                                        <span class="status-badge bg-danger-100 text-danger-800">
                                            <i class="fas fa-times-circle mr-1"></i>Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col space-y-1">
                                        @if($user->bisa_input_panen)
                                            <span class="text-xs text-success-600 flex items-center">
                                                <i class="fas fa-check-circle mr-1"></i>Input Panen
                                            </span>
                                        @else
                                            <span class="text-xs text-danger-600 flex items-center">
                                                <i class="fas fa-times-circle mr-1"></i>Input Panen
                                            </span>
                                        @endif
                                        @if($user->bisa_input_absen)
                                            <span class="text-xs text-success-600 flex items-center">
                                                <i class="fas fa-check-circle mr-1"></i>Input Absen
                                            </span>
                                        @else
                                            <span class="text-xs text-danger-600 flex items-center">
                                                <i class="fas fa-times-circle mr-1"></i>Input Absen
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button onclick="editUser({{ $user->id_user }})" 
                                                class="text-primary-600 hover:text-primary-900 transition-colors p-2 rounded-full hover:bg-primary-50" title="Edit User">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="toggleUserStatus({{ $user->id_user }}, {{ $user->status_aktif ? 0 : 1 }})"
                                                class="@if($user->status_aktif) text-danger-600 hover:text-danger-900 hover:bg-danger-50 @else text-success-600 hover:text-success-900 hover:bg-success-50 @endif transition-colors p-2 rounded-full"
                                                title="{{ $user->status_aktif ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i class="fas {{ $user->status_aktif ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                        </button>
                                        <button onclick="resetPassword({{ $user->id_user }})"
                                                class="text-warning-600 hover:text-warning-900 transition-colors p-2 rounded-full hover:bg-warning-50" title="Reset Password">
                                            <i class="fas fa-key"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($users->isEmpty())
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-users text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada user</h3>
                    <p class="text-gray-500 max-w-md mx-auto mb-6">Tambahkan user baru untuk mulai menggunakan sistem</p>
                    <button onclick="openAddUserModal()" class="bg-success-600 text-white px-4 py-2.5 rounded-lg hover:bg-success-700 transition-all duration-200 card-shadow hover-lift">
                        <i class="fas fa-plus mr-2"></i>Tambah User Pertama
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div id="addUserModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 transition-opacity duration-300">
        <div class="relative top-10 mx-auto p-5 w-full max-w-md">
            <div class="bg-white rounded-xl card-shadow slide-in">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Tambah User Baru</h3>
                    <button onclick="closeAddUserModal()" class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-full hover:bg-gray-100">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
                
                <form id="addUserForm" class="px-6 py-4">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-danger-500">*</span></label>
                        <input type="text" name="nama_lengkap" required 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username <span class="text-danger-500">*</span></label>
                        <input type="text" name="username" required 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-danger-500">*</span></label>
                            <select name="role" required class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                                <option value="karyawan">Karyawan</option>
                                <option value="admin">Admin/Mandor</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan <span class="text-danger-500">*</span></label>
                            <select name="jabatan" required class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                                <option value="anggota">Anggota</option>
                                <option value="asisten_mandor">Asisten Mandor</option>
                                <option value="mandor">Mandor</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                            <input type="text" name="no_telepon" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status Tinggal</label>
                            <select name="status_tinggal" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                                <option value="">Pilih Status</option>
                                <option value="barak">Barak</option>
                                <option value="keluarga_barak">Keluarga Barak</option>
                                <option value="luar">Luar</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                        <button type="button" onclick="closeAddUserModal()" 
                                class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 font-medium">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2.5 bg-success-600 text-white rounded-lg hover:bg-success-700 transition-colors duration-200 font-medium">
                            Simpan User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 transition-opacity duration-300">
        <div class="relative top-10 mx-auto p-5 w-full max-w-md">
            <div class="bg-white rounded-xl card-shadow slide-in">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Edit User</h3>
                    <button onclick="closeEditUserModal()" class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-full hover:bg-gray-100">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
                
                <form id="editUserForm" class="px-6 py-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="user_id" id="edit_user_id">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-danger-500">*</span></label>
                        <input type="text" name="nama_lengkap" id="edit_nama_lengkap" required 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username <span class="text-danger-500">*</span></label>
                        <input type="text" name="username" id="edit_username" required 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-danger-500">*</span></label>
                            <select name="role" id="edit_role" required class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                                <option value="karyawan">Karyawan</option>
                                <option value="admin">Admin/Mandor</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan <span class="text-danger-500">*</span></label>
                            <select name="jabatan" id="edit_jabatan" required class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                                <option value="anggota">Anggota</option>
                                <option value="asisten_mandor">Asisten Mandor</option>
                                <option value="mandor">Mandor</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                            <input type="text" name="no_telepon" id="edit_no_telepon"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status Tinggal</label>
                            <select name="status_tinggal" id="edit_status_tinggal" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                                <option value="">Pilih Status</option>
                                <option value="barak">Barak</option>
                                <option value="keluarga_barak">Keluarga Barak</option>
                                <option value="luar">Luar</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hak Akses</label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="bisa_input_panen" id="edit_bisa_input_panen" class="rounded border-gray-300 text-success-600 focus:ring-success-500">
                                <span class="ml-2 text-sm text-gray-700">Bisa input data panen</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="bisa_input_absen" id="edit_bisa_input_absen" class="rounded border-gray-300 text-success-600 focus:ring-success-500">
                                <span class="ml-2 text-sm text-gray-700">Bisa input absensi</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                        <button type="button" onclick="closeEditUserModal()" 
                                class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 font-medium">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors duration-200 font-medium">
                            Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    // Search functionality
    document.getElementById('searchUser').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.user-row');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // Modal functions
    function openAddUserModal() {
        document.getElementById('addUserModal').classList.remove('hidden');
    }

    function closeAddUserModal() {
        document.getElementById('addUserModal').classList.add('hidden');
        document.getElementById('addUserForm').reset();
    }

    function openEditUserModal() {
        document.getElementById('editUserModal').classList.remove('hidden');
    }

    function closeEditUserModal() {
        document.getElementById('editUserModal').classList.add('hidden');
    }

    // User management functions
    async function editUser(userId) {
        try {
            const response = await fetch(`/owner/users/${userId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                }
            });
            
            if (!response.ok) throw new Error('Gagal mengambil data user');
            
            const user = await response.json();
            
            // Fill form with user data
            document.getElementById('edit_user_id').value = user.id_user;
            document.getElementById('edit_nama_lengkap').value = user.nama_lengkap;
            document.getElementById('edit_username').value = user.username;
            document.getElementById('edit_role').value = user.role;
            document.getElementById('edit_jabatan').value = user.jabatan;
            document.getElementById('edit_no_telepon').value = user.no_telepon || '';
            document.getElementById('edit_status_tinggal').value = user.status_tinggal || '';
            document.getElementById('edit_bisa_input_panen').checked = user.bisa_input_panen;
            document.getElementById('edit_bisa_input_absen').checked = user.bisa_input_absen;
            
            openEditUserModal();
        } catch (error) {
            showNotification('Terjadi kesalahan: ' + error.message, 'error');
        }
    }

    function toggleUserStatus(userId, newStatus) {
        const action = newStatus ? 'mengaktifkan' : 'menonaktifkan';
        if (confirm(`Apakah Anda yakin ingin ${action} user ini?`)) {
            fetch(`/owner/users/${userId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    status_aktif: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(`User berhasil di${action}`, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification('Terjadi kesalahan', 'error');
                }
            })
            .catch(error => {
                showNotification('Terjadi kesalahan: ' + error, 'error');
            });
        }
    }

    function resetPassword(userId) {
        if (confirm('Reset password user ini ke default (password123)?')) {
            fetch(`/owner/users/${userId}/reset-password`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Password berhasil direset ke: password123', 'success');
                } else {
                    showNotification('Terjadi kesalahan', 'error');
                }
            })
            .catch(error => {
                showNotification('Terjadi kesalahan: ' + error, 'error');
            });
        }
    }

    function exportUsers() {
        // Simple export functionality - bisa dikembangkan lebih lanjut
        showNotification('Fitur export akan mengunduh data user dalam format CSV', 'info');
        // Implementasi export bisa menggunakan library seperti SheetJS
    }

    // Form submission
    document.getElementById('addUserForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route("owner.users.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('User berhasil ditambahkan', 'success');
                closeAddUserModal();
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification('Gagal menambahkan user: ' + (data.message || ''), 'error');
            }
        })
        .catch(error => {
            showNotification('Terjadi kesalahan: ' + error, 'error');
        });
    });

    document.getElementById('editUserForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const userId = document.getElementById('edit_user_id').value;
        
        fetch(`/owner/users/${userId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-HTTP-Method-Override': 'PUT'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('User berhasil diperbarui', 'success');
                closeEditUserModal();
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification('Gagal memperbarui user: ' + (data.message || ''), 'error');
            }
        })
        .catch(error => {
            showNotification('Terjadi kesalahan: ' + error, 'error');
        });
    });

    // Notification function
    function showNotification(message, type = 'info') {
        // Remove existing notification if any
        const existingNotification = document.getElementById('custom-notification');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        const notification = document.createElement('div');
        notification.id = 'custom-notification';
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg card-shadow slide-in flex items-center ${ 
            type === 'success' ? 'bg-success-100 text-success-800 border-l-4 border-success-500' : 
            type === 'error' ? 'bg-danger-100 text-danger-800 border-l-4 border-danger-500' : 
            'bg-primary-100 text-primary-800 border-l-4 border-primary-500'
        }`;
        
        const icon = type === 'success' ? 'fa-check-circle' : 
                    type === 'error' ? 'fa-exclamation-circle' : 
                    'fa-info-circle';
        
        notification.innerHTML = `
            <i class="fas ${icon} mr-2"></i>
            <span>${message}</span>
            <button class="ml-4 text-gray-500 hover:text-gray-700" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        const addModal = document.getElementById('addUserModal');
        const editModal = document.getElementById('editUserModal');
        
        if (event.target === addModal) {
            closeAddUserModal();
        }
        
        if (event.target === editModal) {
            closeEditUserModal();
        }
    }
    </script>
</body>
</html>