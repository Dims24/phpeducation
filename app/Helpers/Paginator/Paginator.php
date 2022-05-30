<?php

namespace App\Helpers\Paginator;

class Paginator
{
    #Ключ для query, в который пишется номер страницы
    protected string $key_page = 'page';
    protected string $key_limit = 'limit';

    #Записей на страницу
    protected int $limit;
    #Текущая страница
    protected int $current_page;
    #Последняя страница
    protected int $last_page;
    #Следующая страница
    protected int $per_page;
    #Общее количество записей
    protected int $total;





    # общего числа страниц
    public function setLastPage(): int
    {
        return ceil($this->total / $this->limit);
    }


    #установка текущей страницы
    public function setCurrentPage($currentPage): int
    {
        $this->current_page = $currentPage;


        if ($this->current_page > 0) {
            if ($this->current_page > $this->setLastPage())
                $this->current_page = $this->setLastPage();
        } else
            $this->current_page = 1;
    }
}