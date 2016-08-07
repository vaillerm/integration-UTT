<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NewcomerTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * Check the newcomer button on the homepage
     *
     * @return void
     */
    public function testHomepage()
    {
        $this->visit('/')
            ->click('Je suis nouveau !')
            ->see('Bonjour à toi, ami nouveau !')
            ->see('Tu trouveras les identifiants')
            ->seePageIs(route('newcomer.auth.login'));
    }

    /**
     * Check the newcomer login page with bad credentials
     *
     * @return void
     */
    public function testLoginFail()
    {
        $this->visit(route('newcomer.auth.login'))
            ->type('bulshitLogin', 'login')
            ->type('bulshitPassword', 'password')
            ->press('Se connecter')
            ->seePageIs(route('newcomer.auth.login'))
            ->see('Identifiant ou mot de passe incorrect');
    }

    /**
     * Check the newcomer login page with good credentials
     *
     * @return void
     */
    public function testLoginSuccess()
    {
        $newcomer = factory(App\Models\Newcomer::class)->create();
        $this->visit(route('newcomer.auth.login'))
            ->type($newcomer->login, 'login')
            ->type(Crypt::decrypt($newcomer->password), 'password')
            ->press('Se connecter')
            ->seePageIs(route('newcomer.home'))
            ->see('Vous êtes maintenant connecté.');
    }

    /**
     * Check access to the newcomer home page from not logged user
     *
     * @return void
     */
    public function testHomeFromVisitor()
    {
        // Fail access
        $this->visit(route('newcomer.home'))
            ->seePageIs(route('newcomer.auth.login'))
            ->see('Vous devez être connecté pour accéder à cette page');
    }

    /**
     * Check access to the newcomer home page from logged newcomer
     *
     * @return void
     */
    public function testHomeFromNewcomer()
    {
        // Successful access
        $this->actingAs(factory(App\Models\Newcomer::class)->make())
            ->visit(route('newcomer.home'))
            ->seePageIs(route('newcomer.home'))
            ->see('Bonjour à toi nouveau !');
    }

    /**
     * Check access to the referral page from newcomer with referral
     *
     * @return void
     */
    public function testReferralPageWithReferral()
    {
        $newcomer = factory(App\Models\Newcomer::class)->make();
        $this->actingAs($newcomer)
            ->visit(route('newcomer.referral'))
            ->seePageIs(route('newcomer.referral'))
            ->see($newcomer->referral->first_name.' '.$newcomer->referral->last_name.($newcomer->referral->sex?', ta marraine !':', ton parrain !'));
    }

    /**
     * Check access to the referral page from newcomer without referral
     *
     * @return void
     */
    public function testReferralPageWithoutReferral()
    {
        $newcomer = factory(App\Models\Newcomer::class)->make();
        $newcomer->referral_id = null;
        $this->actingAs($newcomer)
            ->visit(route('newcomer.referral'))
            ->seePageIs(route('newcomer.referral'))
            ->see('Tu rencontrera ton parrain à la rentrée lors de la cérémonie du parrainage le premier jour :)');
    }

    /**
     * Check access to the myLetter page from newcomer with referral and team
     *
     * @return void
     */
    public function testMyletterPageWithReferralAndTeam()
    {
        $newcomer = factory(App\Models\Newcomer::class)->create();
        $this->actingAs($newcomer)
            ->visit(route('newcomer.myletter'))
            ->seePageIs(route('newcomer.myletter'))
            ->see('Ton accès au site de l\'intégration');
    }

    /**
     * Check access to the myLetter page from newcomer without team an referral
     *
     * @return void
     */
    public function testReferralPageWithoutReferralAndTeam()
    {
        $newcomer = factory(App\Models\Newcomer::class)->create();
        $newcomer->referral_id = null;
        $newcomer->team_id = null;
        $this->actingAs($newcomer)
            ->visit(route('newcomer.myletter'))
            ->seePageIs(route('newcomer.myletter'))
            ->see('Ton accès au site de l\'intégration');
    }
}
