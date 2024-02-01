<?php

namespace Transave\ScolaBookstore\Actions\Sales;

use Transave\ScolaBookstore\Helpers\SearchHelper;

class SearchSales
{
    use SearchHelper;

    private function searchTerms()
    {
        $search = $this->searchParam;
        $this->queryBuilder->where(function ($query) use ($search) {
            $query
                ->where('order_id', 'like', "%$search%")
                ->orWhere('total_amount', 'like', "%$search%")
                ->orWhere('invoice_number', 'like', "%$search%")

                ->orWhereHas('order', function ($query1) use ($search) {
                    $query1->where('invoice_number', 'like', "%$search%")
                        ->orWhere('order_date', 'like', "%$search%")
                        ->orWhere('delivery_status', 'like', "%$search%")
                        ->orWhere('payment_status', 'like', "%$search%")
                        ->orWhere('payment_reference', 'like', "%$search%")
                        ->orWhereHas('user', function ($query2) use ($search) {
                            $query2->where('first_name', 'like', "%$search%")
                                    ->orWhere('last_name', 'like', "%$search%")
                                    ->orWhere('email', 'like', "%$search%")
                                    ->orWhere('phone', 'like', "%$search%");
                            });
                 })
                 ->orWhereHas('order.orderItems', function ($query3) use ($search) {
                     $query3->where('resource_type', 'like', "%$search%")
                         ->orWhere('quantity', 'like', "%$search%")
                         ->orWhere('unit_price', 'like', "%$search%")
                         ->orWhere('resource_id', 'like', "%$search%")
                         ->orWhere('total_amount', 'like', "%$search%") 
                         ->orWhereHas('book', function ($bookQuery) use ($search) {
                            $bookQuery->where('title', 'like', "%$search%")
                              ->orWhere('primary_author', 'like', "%$search%")
                              ->orWhere('contributors', 'like', "%$search%")
                              ->orWhere('ISBN', 'like', "%$search%")
                              ->orWhere('publication_date', 'like', "%$search%")
                              ->orWhere('publisher', 'like', "%$search%")
                              ->orWhere('edition', 'like', "%$search%")
                              ->orWhereHas('user', function ($bookQuery1) use ($search) {
                                             $bookQuery1->where('first_name', 'like', "%$search%")
                                             ->orWhere('last_name', 'like', "%$search%")
                                             ->orWhere('email', 'like', "%$search%");
                              })
                              ->orWhereHas('category', function ($bookQuery2) use ($search) {
                                             $bookQuery2->where('name', 'like', "%$search%");
                              })
                              ->orWhereHas('publisher', function ($bookQuery3) use ($search) {
                                             $bookQuery3->where('name', 'like', "%$search%");
                              });
                       })
                        ->orWhereHas('report', function ($reportQuery) use ($search) {
                           $reportQuery->where('title', 'like', "%$search%")
                              ->orWhere('publisher', 'like', "%$search%") 
                              ->orWhere('organization', 'like', "%$search%") 
                              ->orWhere('publication_date', 'like', "%$search%")
                              ->orWhere('publication_year', 'like', "%$search%")
                              ->orWhere('report_number', 'like', "%$search%")
                              ->orWhere('institutional_affiliations', 'like', "%$search%")
                              ->orWhere('primary_author', 'like', "%$search%")
                              ->orWhere('contributors', 'like', "%$search%")
                              ->orWhere('keywords', 'like', "%$search%")
                              ->orWhere('price', 'like', "%$search%")
                              ->orWhereHas('user', function ($reportQuery1) use ($search) {
                              $reportQuery1->where('first_name', 'like', "%$search%")
                              ->orWhere('last_name', 'like', "%$search%")
                              ->orWhere('email', 'like', "%$search%");
                              })
                              ->orWhereHas('category', function ($reportQuery2) use ($search) {
                              $reportQuery2->where('name', 'like', "%$search%");
                              })
                              ->orWhereHas('publisher', function ($reportQuery3) use ($search) {
                              $reportQuery3->where('name', 'like', "%$search%");
                              });
                     })
                       ->orWhereHas('journal', function ($journalQuery) use ($search) {
                           $journalQuery->where('title', 'like', "%$search%")
                              ->orWhere('editorial_board_members', 'like', "%$search%")
                              ->orWhere('website', 'like', "%$search%")
                              ->orWhere('editors', 'like', "%$search%")
                              ->orWhere('editorial_board_members', 'like', "%$search%")
                              ->orWhere('publication_date', 'like', "%$search%")
                              ->orWhere('publication_year', 'like', "%$search%")
                              ->orWhere('volume', 'like', "%$search%")
                              ->orWhere('page_start', 'like', "%$search%")
                              ->orWhere('page_end', 'like', "%$search%")
                              ->orWhere('publisher', 'like', "%$search%")
                              ->orWhere('price', 'like', "%$search%")
                              ->orWhereHas('user', function ($journalQuery1) use ($search) {
                                             $journalQuery1->where('first_name', 'like', "%$search%")
                                             ->orWhere('last_name', 'like', "%$search%")
                                             ->orWhere('email', 'like', "%$search%");
                              })
                              ->orWhereHas('category', function ($journalQuery2) use ($search) {
                                             $journalQuery2->where('name', 'like', "%$search%");
                              })
                              ->orWhereHas('publisher', function ($journalQuery3) use ($search) {
                                             $journalQuery3->where('name', 'like', "%$search%");
                              });
                   })
                   ->orWhereHas('festchrisft', function ($festchrisftQuery) use ($search) {
                              $festchrisftQuery->where('title', 'like', "%$search%")
                                             ->orWhere('publisher', 'like', "%$search%")
                                             ->orWhere('editors', 'like', "%$search%")
                                             ->orWhere('keywords', 'like', "%$search%")
                                             ->orWhere('publication_date', 'like', "%$search%")
                                             ->orWhere('dedicatees', 'like', "%$search%")
                                             ->orWhere('price', 'like', "%$search%");
                   })
                    ->orWhereHas('conference_paper', function ($conferencePaperQuery) use ($search) {
                              $conferencePaperQuery->where('title', 'like', "%$search%")
                                             ->orWhere('subtitle', 'like', "%$search%")
                                             ->orWhere('primary_author', 'like', "%$search%")
                                             ->orWhere('contributors', 'like', "%$search%")
                                             ->orWhere('keywords', 'like', "%$search%")
                                             ->orWhere('conference_date', 'like', "%$search%")
                                             ->orWhere('conference_name', 'like', "%$search%")
                                             ->orWhere('conference_year', 'like', "%$search%")
                                             ->orWhere('conference_location', 'like', "%$search%")
                                             ->orWhere('institutional_affiliations', 'like', "%$search%")
                                             ->orWhere('price', 'like', "%$search%")
                                             ->orWhere('keywords', 'like', "%$search%");
                   })
                    ->orWhereHas('research_resource', function ($researchResourceQuery) use ($search) {
                              $researchResourceQuery->where('title', 'like', "%$search%")
                                             ->orWhere('primary_author', 'like', "%$search%")
                                             ->orWhere('publisher', 'like', "%$search%")
                                             ->orWhere('contributors', 'like', "%$search%")
                                             ->orWhere('keywords', 'like', "%$search%")
                                             ->orWhere('publication_date', 'like', "%$search%")
                                             ->orWhere('publication_year', 'like', "%$search%")
                                             ->orWhere('source', 'like', "%$search%")
                                             ->orWhere('resource_url', 'like', "%$search%")
                                             ->orWhere('resource_type', 'like', "%$search%")
                                             ->orWhere('keywords', 'like', "%$search%")
                                             ->orWhere('price', 'like', "%$search%")
                                             ->orWhereHas('user', function ($researchResourceQuery1) use ($search) {
                                             $researchResourceQuery1->where('first_name', 'like', "%$search%")
                                             ->orWhere('last_name', 'like', "%$search%")
                                             ->orWhere('email', 'like', "%$search%");
                                             })
                                             ->orWhereHas('category', function ($researchResourceQuery2) use ($search) {
                                             $researchResourceQuery2->where('name', 'like', "%$search%");
                                             })
                                             ->orWhereHas('publisher', function ($researchResourceQuery3) use ($search) {
                                             $researchResourceQuery3->where('name', 'like', "%$search%");
                                             });
                   })
                   ->orWhereHas('monograph', function ($monographQuery) use ($search) {
                              $monographQuery->where('title', 'like', "%$search%")
                                             ->orWhere('primary_author', 'like', "%$search%")
                                             ->orWhere('publisher', 'like', "%$search%")
                                             ->orWhere('contributors', 'like', "%$search%")
                                             ->orWhere('keywords', 'like', "%$search%")
                                             ->orWhere('publication_date', 'like', "%$search%")
                                             ->orWhere('publication_year', 'like', "%$search%")
                                             ->orWhere('ISBN', 'like', "%$search%")
                                             ->orWhere('edition', 'like', "%$search%")
                                             ->orWhere('keywords', 'like', "%$search%")
                                             ->orWhere('price', 'like', "%$search%")
                                             ->orWhereHas('user', function ($monographQuery1) use ($search) {
                                             $monographQuery1->where('first_name', 'like', "%$search%")
                                             ->orWhere('last_name', 'like', "%$search%")
                                             ->orWhere('email', 'like', "%$search%");
                                             })
                                             ->orWhereHas('category', function ($monographQuery2) use ($search) {
                                             $monographQuery2->where('name', 'like', "%$search%");
                                             })
                                             ->orWhereHas('publisher', function ($monographQuery3) use ($search) {
                                             $monographQuery3->where('name', 'like', "%$search%");
                                             });
                    })
                     ->orWhereHas('article', function ($articleQuery) use ($search) {
                              $articleQuery->where('title', 'like', "%$search%")
                              ->orWhere('publisher', 'like', "%$search%")
                              ->orWhere('primary_author', 'like', "%$search%")
                              ->orWhere('contributors', 'like', "%$search%")
                              ->orWhere('publication_date', 'like', "%$search%")
                              ->orWhere('keywords', 'like', "%$search%")
                              ->orWhere('price', 'like', "%$search%")
                              ->orWhereHas('user', function ($articleQuery1) use ($search) {
                                             $articleQuery1->where('first_name', 'like', "%$search%")
                                             ->orWhere('last_name', 'like', "%$search%")
                                             ->orWhere('email', 'like', "%$search%");
                              })
                              ->orWhereHas('category', function ($articleQuery2) use ($search) {
                                             $articleQuery2->where('name', 'like', "%$search%");
                              })
                              ->orWhereHas('publisher', function ($articleQuery3) use ($search) {
                                             $articleQuery3->where('name', 'like', "%$search%");
                              });
                   });
                 });
        }); 

        return $this;
    }
}
