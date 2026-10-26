<?php

namespace App\Filament\Widgets;

use App\Models\Club;
use App\Models\ClubTeam;
use App\Models\Player;
use App\Models\ScoutingReport;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MasariOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalPlayers = Player::count();
        $totalClubs = Club::count();
        $totalTeams = ClubTeam::count();
        $totalReports = ScoutingReport::count();

        return [
            Stat::make('اللاعبون الشبان الموثقون', number_format($totalPlayers))
                ->description('بالجواز الرياضي الرقمي ورمز QR')
                ->descriptionIcon('heroicon-m-identification')
                ->color('success')
                ->chart([20, 50, 150, 450, 1200, 3500, $totalPlayers]),

            Stat::make('أندية الرابطة المنخرطة', $totalClubs)
                ->description('عبر 5 ولايات جهوية والشلف')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('primary'),

            Stat::make('الفرق الشبانية المعتمدة', $totalTeams)
                ->description('فئات U20, U18, U17, U16, U15')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning'),

            Stat::make('بنك تقارير المعاينة الفنية', $totalReports)
                ->description('المواهب المرصودة للانتقاء الجهوي')
                ->descriptionIcon('heroicon-m-star')
                ->color('danger'),
        ];
    }
}
