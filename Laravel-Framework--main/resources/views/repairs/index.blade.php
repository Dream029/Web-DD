<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">รายการแจ้งซ่อม</h2>
            <a href="{{ route('repairs.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">+ แจ้งซ่อมใหม่</a>
        </div>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b bg-gray-100">
                        <th class="p-3">หัวข้อปัญหา</th>
                        <th class="p-3">สถานที่</th>
                        <th class="p-3">ความเร่งด่วน</th>
                        <th class="p-3">สถานะ</th>
                        <th class="p-3">การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $item)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 font-bold">{{ $item->title }}</td>
                            <td class="p-3">{{ $item->location }}</td>
                            <td class="p-3">
                                <span
                                    class="px-2 py-1 rounded text-xs text-white bg-{{ $item->priority == 'urgent' ? 'red-600' : 'gray-500' }}">
                                    {{ strtoupper($item->priority) }}
                                </span>
                            </td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-800">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="p-3">
                                <a href="{{ route('repairs.show', $item->id) }}"
                                    class="text-blue-600 underline">ดูรายละเอียด</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-500">ยังไม่มีรายการแจ้งซ่อม</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>