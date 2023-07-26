<?php

namespace App\Service;

use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChartService
{

    public function __construct(
        private ChartBuilderInterface $chartBuilder
    ) {
    }

    // Pie Chart
    public function createPieChart(array $labels, array $data)
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_PIE);

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'backgroundColor' => [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)'
                    ],
                    'data' => $data,
                    'hoverOffset' => 4
                ],
            ],
        ]);

        $chart->setOptions([
            'plugins' => [
                'legend' => [
                    'display' => false
                ]
            ]
        ]);

        return $chart;
    }


    // Donut Chart
    public function createDonutChart(array $labels, array $data)
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_DOUGHNUT);

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'backgroundColor' => [
                        'rgb(114, 145, 230)',
                        'rgb(236, 242, 255)',
                        'rgb(237, 243, 252)'
                    ],
                    'data' => $data,
                    'hoverOffset' => 4
                ],
            ],
        ]);

        $chart->setOptions([
            'plugins' => [
                'legend' => [
                    'display' => false
                ]
            ],
            'cutout' => '70%'

        ]);

        return $chart;
    }

    // Polar Chart
    public function createPolarChart(array $labels, array $data)
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_POLAR_AREA);

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'backgroundColor' => [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        'rgb(61,217,235)',
                        'rgb(92, 92, 92)'
                    ],
                    'data' => $data,
                ],
            ]
        ]);

        $chart->setOptions([
            'plugins' => [
                'legend' => [
                    'display' => false
                ]
            ]
        ]);

        return $chart;
    }

    // Bar Chart
    public function createBarChart(array $labels = [], array $data = [])
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_BAR);

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Subscription Chart',
                    'backgroundColor' => [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)'
                    ],
                    'data' => $data,
                    'hoverOffset' => 4,
                    'borderWidth' => 1
                ],
            ],
        ]);

        return $chart;
    }

    // Line Chart
    public function createLineChart(array $labels, array $data)
    {
        
        $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Company',
                    'data' => $data,
                    'fill' => false,
                    'borderColor' => 'rgb(75, 192, 192)',
                    'tension' => 0.1,

                    'hoverOffset' => 4,
                    'borderWidth' => 1
                ],
            ],
        ]);

        return $chart;
    }
}
