<?php

namespace App\View\Components\organism;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class aside extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $sideNavDashboard = [
            'Dashboard' => [
                'url' => '',
                'viewBox' => '0 0 24 24',
                'paths' => [
                    'M6.5 17.5L6.5 14.5M11.5 17.5L11.5 8.5M16.5 17.5V13.5',
                    'M21.5 5.5C21.5 7.15685 20.1569 8.5 18.5 8.5C16.8431 8.5 15.5 7.15685 15.5 5.5C15.5 3.84315 16.8431 2.5 18.5 2.5C20.1569 2.5 21.5 3.84315 21.5 5.5Z',
                    'M21.4955 11C21.4955 11 21.5 11.3395 21.5 12C21.5 16.4784 21.5 18.7175 20.1088 20.1088C18.7175 21.5 16.4783 21.5 12 21.5C7.52166 21.5 5.28249 21.5 3.89124 20.1088C2.5 18.7175 2.5 16.4784 2.5 12C2.5 7.52169 2.5 5.28252 3.89124 3.89127C5.28249 2.50003 7.52166 2.50003 12 2.50003L13 2.5',
                ],
            ],
        ];

        $sideNavPetshop = [
            'Petshop' => [
                'url' => 'data-alternatif',
                'viewBox' => '0 0 24 24',
                'paths' => [
                    'M17.0235 3.03358L16.0689 2.77924C13.369 2.05986 12.019 1.70018 10.9555 2.31074C9.89196 2.9213 9.53023 4.26367 8.80678 6.94841L7.78366 10.7452C7.0602 13.4299 6.69848 14.7723 7.3125 15.8298C7.92652 16.8874 9.27651 17.247 11.9765 17.9664L12.9311 18.2208C15.631 18.9401 16.981 19.2998 18.0445 18.6893C19.108 18.0787 19.4698 16.7363 20.1932 14.0516L21.2163 10.2548C21.9398 7.57005 22.3015 6.22768 21.6875 5.17016C21.0735 4.11264 19.7235 3.75295 17.0235 3.03358Z',
                    'M16.8538 7.43306C16.8538 8.24714 16.1901 8.90709 15.3714 8.90709C14.5527 8.90709 13.889 8.24714 13.889 7.43306C13.889 6.61898 14.5527 5.95904 15.3714 5.95904C16.1901 5.95904 16.8538 6.61898 16.8538 7.43306Z',
                    'M12 20.9463L11.0477 21.2056C8.35403 21.9391 7.00722 22.3059 5.94619 21.6833C4.88517 21.0608 4.52429 19.6921 3.80253 16.9547L2.78182 13.0834C2.06006 10.346 1.69918 8.97731 2.31177 7.89904C2.84167 6.96631 4 7.00027 5.5 7.00015'
                ],
            ],

        ];

        $sideNavKriteria = [
            'Data Kriteria' => [
                'url' => 'data-kriteria'
            ],
            'Data Subkriteria' => [
                'url' => 'data-subkriteria'
            ],
            'Skala Indikator' => [
                'url' => 'data-skala-indikator'
            ],
        ];

        $sideNavPenilaian = [
            'Penilaian' => [
                'url' => 'data-penilaian',
                'viewBox' => '0 0 24 24',
                'path' => 'M4.098 19.902a3.75 3.75 0 005.304 0l6.401-6.402M6.75 21A3.75 3.75 0 013 17.25V4.125C3 3.504 3.504 3 4.125 3h5.25c.621 0 1.125.504 1.125 1.125v4.072M6.75 21a3.75 3.75 0 003.75-3.75V8.197M6.75 21h13.125c.621 0 1.125-.504 1.125-1.125v-5.25c0-.621-.504-1.125-1.125-1.125h-4.072M10.5 8.197l2.88-2.88c.438-.439 1.15-.439 1.59 0l3.712 3.713c.44.44.44 1.152 0 1.59l-2.879 2.88M6.75 17.25h.008v.008H6.75v-.008z',
            ],
        ];

        $sideNavPerhitungan = [
            'Kriteria' => [
                'url' => 'perbandingan-kriteria'
            ],
            'Perankingan' => [
                'url' => 'perankingan'
            ],
        ];


        return view('components.organism.aside', compact('sideNavDashboard', 'sideNavPenilaian', 'sideNavPerhitungan', 'sideNavPetshop', 'sideNavKriteria'));
    }
}
