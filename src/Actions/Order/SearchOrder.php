<?php

namespace Transave\ScolaBookstore\Actions\Order;

use Transave\ScolaBookstore\Helpers\SearchHelper;

class SearchOrder
{
    use SearchHelper;

    private function searchTerms()
    {
        $search = $this->searchParam;
        $this->queryBuilder->where(function ($query) use ($search) {
            $query
                ->where('status', 'like', "%$search%")
                ->orWhere('invoice_no', 'like', "%$search%")
                ->orWhereHas('user', function ($query) use ($search) {
                    $query->where('first_name', 'like', "%$search%")
                        ->orWhere('last_name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                })
                ->orWhereHas('book', function ($query1) use ($search) {
                    $query1->where('title', 'like', "%$search%")
                        ->orWhere('primary_author', 'like', "%$search%")
                        ->orWhere('contributors', 'like', "%$search%")
                        ->orWhere('ISBN', 'like', "%$search%")
                        ->orWhere('publication_date', 'like', "%$search%")
                        ->orWhere('publisher', 'like', "%$search%")//during registration, user's enter new publisher if the publisher been searched is not registered/found.
                        ->orWhere('edition', 'like', "%$search%")
                            ->orWhereHas('user', function ($query21) use ($search) {
                                $query21->where('first_name', 'like', "%$search%")
                                       ->orWhere('last_name', 'like', "%$search%")
                                       ->orWhere('email', 'like', "%$search%");
                            })
                            ->orWhereHas('category', function ($query22) use ($search) {
                                $query22->where('name', 'like', "%$search%");
                            })
                            ->orWhereHas('publisher', function ($query23) use ($search) {
                                $query23->where('name', 'like', "%$search%");
                            });
                });
                // ->orWhereHas('monograph', function ($query2) use ($search) {
                //     $query2->where('title', 'like', "%$search%")
                //         ->orWhere('primary_author', 'like', "%$search%")
                //         ->orWhere('publisher', 'like', "%$search%") //during registration, user's enter new publisher if the publisher been searched is not registered/found.
                //         ->orWhere('contributors', 'like', "%$search%")
                //         ->orWhere('keywords', 'like', "%$search%")
                //         ->orWhere('publication_date', 'like', "%$search%")
                //         ->orWhere('publication_year', 'like', "%$search%")
                //         ->orWhere('ISBN', 'like', "%$search%")
                //         ->orWhere('edition', 'like', "%$search%")
                //         ->orWhere('keywords', 'like', "%$search%")
                //         ->orWhere('price', 'like', "%$search%")
                //             ->orWhereHas('user', function ($query21) use ($search) {
                //                 $query21->where('first_name', 'like', "%$search%")
                //                        ->orWhere('last_name', 'like', "%$search%")
                //                        ->orWhere('email', 'like', "%$search%");
                //             })
                //             ->orWhereHas('category', function ($query22) use ($search) {
                //                 $query22->where('name', 'like', "%$search%");
                //             })
                //             ->orWhereHas('publisher', function ($query23) use ($search) {
                //                 $query23->where('name', 'like', "%$search%");
                //             });
                // })
                // ->orWhereHas('article', function ($query3) use ($search) {
                //     $query3->where('title', 'like', "%$search%")
                //         ->orWhere('publisher', 'like', "%$search%")//during registration, user's enter new publisher if the publisher been searched is not registered/found.
                //         ->orWhere('primary_author', 'like', "%$search%")
                //         ->orWhere('contributors', 'like', "%$search%")
                //         ->orWhere('publication_date', 'like', "%$search%")
                //         ->orWhere('keywords', 'like', "%$search%")
                //         ->orWhere('price', 'like', "%$search%")
                //             ->orWhereHas('user', function ($query31) use ($search) {
                //                 $query31->where('first_name', 'like', "%$search%")
                //                        ->orWhere('last_name', 'like', "%$search%")
                //                        ->orWhere('email', 'like', "%$search%");
                //             })
                //             ->orWhereHas('category', function ($query32) use ($search) {
                //                 $query32->where('name', 'like', "%$search%");
                //             })
                //             ->orWhereHas('publisher', function ($query33) use ($search) {
                //                 $query33->where('name', 'like', "%$search%");
                //             });
                // })
                // ->orWhereHas('journal', function ($query4) use ($search) {
                //     $query4->where('title', 'like', "%$search%")
                //         ->orWhere('editorial_board_members', 'like', "%$search%")
                //         ->orWhere('website', 'like', "%$search%")
                //         ->orWhere('editors', 'like', "%$search%")
                //         ->orWhere('editorial_board_members', 'like', "%$search%")
                //         ->orWhere('publication_date', 'like', "%$search%")
                //         ->orWhere('publication_year', 'like', "%$search%")
                //         ->orWhere('volume', 'like', "%$search%")
                //         ->orWhere('page_start', 'like', "%$search%")
                //         ->orWhere('page_end', 'like', "%$search%")
                //         ->orWhere('publisher', 'like', "%$search%")//during registration, user's enter new publisher if the publisher been searched is not registered/found.
                //         ->orWhere('price', 'like', "%$search%")
                //             ->orWhereHas('user', function ($query41) use ($search) {
                //                 $query41->where('first_name', 'like', "%$search%")
                //                     ->orWhere('last_name', 'like', "%$search%")
                //                     ->orWhere('email', 'like', "%$search%");
                //             })
                //             ->orWhereHas('category', function ($query42) use ($search) {
                //                 $query42->where('name', 'like', "%$search%");
                //             })
                //             ->orWhereHas('publisher', function ($query43) use ($search) {
                //                 $query43->where('name', 'like', "%$search%");
                //             });
                // })
                // ->orWhereHas('festchrisft', function ($query5) use ($search) {
                //     $query5->where('publisher', 'like', "%$search%")
                //         ->orWhere('title', 'like', "%$search%")
                //         ->orWhere('editors', 'like', "%$search%")
                //         ->orWhere('keywords', 'like', "%$search%")
                //         ->orWhere('publication_date', 'like', "%$search%")
                //         ->orWhere('dedicatees', 'like', "%$search%")
                //         ->orWhere('price', 'like', "%$search%")
                //             ->orWhereHas('user', function ($query51) use ($search) {
                //                 $query51->where('first_name', 'like', "%$search%")
                //                     ->orWhere('last_name', 'like', "%$search%")
                //                     ->orWhere('email', 'like', "%$search%");
                //             })
                //             ->orWhereHas('category', function ($query52) use ($search) {
                //                 $query52->where('name', 'like', "%$search%");
                //             })
                //             ->orWhereHas('publisher', function ($query53) use ($search) {
                //                 $query53->where('name', 'like', "%$search%");
                //             });
                // })
                // ->orWhereHas('conference_paper', function ($query6) use ($search) {
                //     $query6->where('title', 'like', "%$search%")
                //         ->orWhere('subtitle', 'like', "%$search%")
                //         ->orWhere('primary_author', 'like', "%$search%")
                //         ->orWhere('contributors', 'like', "%$search%")
                //         ->orWhere('keywords', 'like', "%$search%")
                //         ->orWhere('conference_date', 'like', "%$search%")
                //         ->orWhere('conference_name', 'like', "%$search%")
                //         ->orWhere('conference_year', 'like', "%$search%")
                //         ->orWhere('conference_location', 'like', "%$search%")
                //         ->orWhere('institutional_affiliations', 'like', "%$search%")
                //         ->orWhere('price', 'like', "%$search%")
                //         ->orWhere('keywords', 'like', "%$search%")
                //             ->orWhereHas('user', function ($query61) use ($search) {
                //                 $query61->where('first_name', 'like', "%$search%")
                //                     ->orWhere('last_name', 'like', "%$search%")
                //                     ->orWhere('email', 'like', "%$search%");
                //             })
                //             ->orWhereHas('category', function ($query62) use ($search) {
                //                 $query62->where('name', 'like', "%$search%");
                //             });
                // })
                // ->orWhereHas('report', function ($query7) use ($search) {
                //     $query7->where('title', 'like', "%$search%")
                //         ->orWhere('publisher', 'like', "%$search%") //during registration, user's enter new publisher if the publisher been searched is not registered/found.
                //         ->orWhere('organization', 'like', "%$search%") 
                //         ->orWhere('publication_date', 'like', "%$search%")
                //         ->orWhere('publication_year', 'like', "%$search%")
                //         ->orWhere('report_number', 'like', "%$search%")
                //         ->orWhere('institutional_affiliations', 'like', "%$search%")
                //         ->orWhere('primary_author', 'like', "%$search%")
                //         ->orWhere('contributors', 'like', "%$search%")
                //         ->orWhere('keywords', 'like', "%$search%")
                //         ->orWhere('price', 'like', "%$search%")
                //             ->orWhereHas('user', function ($query71) use ($search) {
                //                 $query71->where('first_name', 'like', "%$search%")
                //                     ->orWhere('last_name', 'like', "%$search%")
                //                     ->orWhere('email', 'like', "%$search%");
                //             })
                //             ->orWhereHas('category', function ($query72) use ($search) {
                //                 $query72->where('name', 'like', "%$search%");
                //             })
                //             ->orWhereHas('publisher', function ($query73) use ($search) {
                //                 $query73->where('name', 'like', "%$search%");
                //             });
                // })
                //  ->orWhereHas('research_resource', function ($query8) use ($search) {
                //     $query8->where('title', 'like', "%$search%")
                //         ->orWhere('primary_author', 'like', "%$search%")
                //         ->orWhere('publisher', 'like', "%$search%") //during registration, user's enter new publisher if the publisher been searched is not registered/found.
                //         ->orWhere('contributors', 'like', "%$search%")
                //         ->orWhere('keywords', 'like', "%$search%")
                //         ->orWhere('publication_date', 'like', "%$search%")
                //         ->orWhere('publication_year', 'like', "%$search%")
                //         ->orWhere('source', 'like', "%$search%")
                //         ->orWhere('resource_url', 'like', "%$search%")
                //         ->orWhere('resource_type', 'like', "%$search%")
                //         ->orWhere('keywords', 'like', "%$search%")
                //         ->orWhere('price', 'like', "%$search%")
                //             ->orWhereHas('user', function ($query81) use ($search) {
                //                 $query81->where('first_name', 'like', "%$search%")
                //                     ->orWhere('last_name', 'like', "%$search%")
                //                     ->orWhere('email', 'like', "%$search%");
                //             })
                //             ->orWhereHas('category', function ($query82) use ($search) {
                //                 $query82->where('name', 'like', "%$search%");
                //             })
                //             ->orWhereHas('publisher', function ($query83) use ($search) {
                //                 $query83->where('name', 'like', "%$search%");
                //             });
                //  });

     
        });

        return $this;
    }
}
